<?php

namespace NextDeveloper\Support\Services;

use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Support\Database\Models\AgentExpertises;
use NextDeveloper\Support\Database\Models\TicketAudits;
use NextDeveloper\Support\Database\Models\Tickets;

/**
 * Synchronous ticket lifecycle: status changes, assignment, skill routing and SLA
 * escalation. These used to be queued Actions, which meant an agent clicking a status
 * waited on a worker before the change showed up. Every operation now runs inline in
 * the request, so the caller gets the settled ticket back immediately.
 *
 * Domain events are still fired, so notification listeners and NATS live-sync behave
 * exactly as they did under the Action implementation.
 */
class TicketWorkflowService
{
    public const STATUSES = ['open', 'pending', 'waiting_on_customer', 'resolved', 'closed'];

    public const CLOSED_STATUSES = ['resolved', 'closed'];

    public const OPEN_STATUSES = ['open', 'pending', 'waiting_on_customer'];

    /**
     * Moves a ticket through its lifecycle and keeps the derived columns consistent:
     *  - resolved/closed stamp resolved_at and is_closed
     *  - re-opening a resolved/closed ticket increments reopened_count and clears resolution
     *  - first-contact-resolution is flagged when a ticket is resolved without ever re-opening
     *
     * @return array{status: string, message: string, ticket?: Tickets}
     */
    public static function changeStatus(Tickets $ticket, ?string $status): array
    {
        $new = (string) $status;
        $old = (string) $ticket->status;

        if (! in_array($new, self::STATUSES, true)) {
            return self::error('Invalid status: '.$new);
        }

        if ($new === $old) {
            return self::success('Ticket already in status '.$new, $ticket);
        }

        //  Customers (non support staff) may only close their own ticket, not move it
        //  through the agent workflow (pending/resolved/etc.).
        if (! self::isAgent() && $new !== 'closed') {
            return self::error('You can only close this ticket.');
        }

        $data = ['status' => $new];

        $wasClosed = in_array($old, self::CLOSED_STATUSES, true);
        $isClosing = in_array($new, self::CLOSED_STATUSES, true);

        if ($isClosing && ! $wasClosed) {
            $data['resolved_at'] = $ticket->resolved_at ?? now();
            $data['is_closed'] = $new === 'closed';
            $data['is_first_contact_resolution'] = (int) $ticket->reopened_count === 0;
        }

        if ($wasClosed && ! $isClosing) {
            $data['reopened_count'] = (int) $ticket->reopened_count + 1;
            $data['resolved_at'] = null;
            $data['is_closed'] = false;
            $data['is_first_contact_resolution'] = false;
        }

        if (! $isClosing && ! $wasClosed) {
            $data['is_closed'] = false;
        }

        $actorId = UserHelper::me() ? UserHelper::me()->id : null;

        TicketsService::privilegedUpdate($ticket, $data);

        self::writeAudit($ticket, $old, $new, $actorId);

        $fresh = self::refresh($ticket);

        Events::fire('status-changed:NextDeveloper\Support\Tickets', $fresh, [
            'old' => $old,
            'new' => $new,
            'actor_id' => $actorId,
        ]);

        return self::success('Ticket status changed from '.$old.' to '.$new, $fresh);
    }

    /**
     * Assigns a ticket to an agent by writing responsible_user_id. The ticket-assigned
     * event is fired so notification listeners/pushers can inform the agent.
     *
     * @param  string|int|null  $agentRef  user uuid or id
     * @return array{status: string, message: string, ticket?: Tickets}
     */
    public static function assign(Tickets $ticket, $agentRef): array
    {
        if (! $agentRef) {
            return self::error('No agent was given to assign this ticket to.');
        }

        $agent = UserHelper::getWithId($agentRef);

        if (! $agent) {
            return self::error('The specified agent could not be found.');
        }

        TicketsService::privilegedUpdate($ticket, [
            'responsible_user_id' => $agent->id,
        ]);

        $fresh = self::refresh($ticket);

        Events::fire('ticket-assigned:NextDeveloper\Support\Tickets', $fresh);

        return self::success('Ticket assigned to '.$agent->fullname, $fresh);
    }

    /**
     * Removes the agent assignment from a ticket.
     *
     * @return array{status: string, message: string, ticket?: Tickets}
     */
    public static function unassign(Tickets $ticket): array
    {
        TicketsService::privilegedUpdate($ticket, [
            'responsible_user_id' => null,
        ]);

        $fresh = self::refresh($ticket);

        Events::fire('ticket-unassigned:NextDeveloper\Support\Tickets', $fresh);

        return self::success('Ticket unassigned', $fresh);
    }

    /**
     * Skill-based routing: assigns a newly created ticket to the most suitable agent for
     * its category - highest proficiency, currently available, and least loaded with open
     * tickets. Does nothing if the ticket is already assigned or has no category.
     *
     * @return array{status: string, message: string, ticket?: Tickets}
     */
    public static function autoRoute(Tickets $ticket): array
    {
        if ($ticket->responsible_user_id) {
            return self::success('Ticket already assigned; skipping auto-route.', $ticket);
        }

        if (! $ticket->common_category_id) {
            return self::success('Ticket has no category; skipping auto-route.', $ticket);
        }

        $candidates = AgentExpertises::withoutGlobalScope(AuthorizationScope::class)
            ->where('common_category_id', $ticket->common_category_id)
            ->where('is_available', true)
            ->whereNull('deleted_at')
            ->orderByDesc('proficiency')
            ->get();

        if ($candidates->isEmpty()) {
            return self::success('No available agent with matching expertise; left unassigned.', $ticket);
        }

        $best = $candidates->sortBy(fn ($expertise) => self::openLoad($expertise->iam_user_id))->first();

        TicketsService::privilegedUpdate($ticket, [
            'responsible_user_id' => $best->iam_user_id,
        ]);

        $fresh = self::refresh($ticket);

        Events::fire('ticket-routed:NextDeveloper\Support\Tickets', $fresh);

        return self::success('Ticket routed to agent #'.$best->iam_user_id, $fresh);
    }

    /**
     * Escalates a ticket whose SLA has been breached: raises its priority (capped at 5)
     * and fires the sla-breached event so notification listeners can alert the team.
     * Called by CheckTicketSlaBreachesJob, which sets the breach flags first.
     *
     * @return array{status: string, message: string, ticket?: Tickets}
     */
    public static function escalateOnSlaBreach(Tickets $ticket, ?string $type = null): array
    {
        $newPriority = min((int) $ticket->priority + 1, 5);

        TicketsService::privilegedUpdate($ticket, [
            'priority' => $newPriority,
            'level' => min((int) $ticket->level + 1, 5),
        ]);

        $fresh = self::refresh($ticket);

        Events::fire('sla-breached:NextDeveloper\Support\Tickets', $fresh, [
            'type' => $type,
        ]);

        return self::success('Ticket escalated to priority '.$newPriority.' on SLA breach', $fresh);
    }

    private static function isAgent(): bool
    {
        return UserHelper::hasRole('support-admin') || UserHelper::hasRole('support-specialist');
    }

    /**
     * Counts the open tickets an agent is currently carrying, used to break proficiency ties.
     */
    private static function openLoad(int $agentId): int
    {
        return Tickets::withoutGlobalScope(AuthorizationScope::class)
            ->where('responsible_user_id', $agentId)
            ->whereIn('status', self::OPEN_STATUSES)
            ->whereNull('deleted_at')
            ->count();
    }

    /**
     * Drops the ticket caches and reloads the row past the authorization scope, so the
     * caller always gets the settled record even when the writer was an elevated admin.
     */
    private static function refresh(Tickets $ticket): Tickets
    {
        CacheHelper::deleteKeys(Tickets::class, $ticket->uuid);

        return Tickets::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $ticket->id)
            ->first() ?? $ticket;
    }

    /**
     * @param  int|null  $actorId  captured before elevating - inside runAsAdmin, me() is the admin
     */
    private static function writeAudit(Tickets $ticket, string $old, string $new, ?int $actorId): void
    {
        $actorId = $actorId ?? $ticket->iam_user_id;

        try {
            UserHelper::runAsAdmin(function () use ($ticket, $old, $new, $actorId): void {
                UserHelper::bypassRolesCheck(true);

                try {
                    //  iam_account_id is set from the ticket, not left to the observer: inside
                    //  runAsAdmin the current account is the admin's, not the ticket owner's.
                    TicketAudits::create([
                        'support_ticket_id' => $ticket->id,
                        'comments' => 'Status changed from '.$old.' to '.$new,
                        'iam_user_id' => $actorId,
                        'iam_account_id' => $ticket->iam_account_id,
                        'point' => 0,
                    ]);
                } finally {
                    UserHelper::bypassRolesCheck(false);
                }
            });
        } catch (\Exception $e) {
            //  Audit is best-effort; never block a status change because of it.
        }
    }

    /**
     * @return array{status: string, message: string, ticket: Tickets}
     */
    private static function success(string $message, Tickets $ticket): array
    {
        return [
            'status' => 'success',
            'message' => $message,
            'ticket' => $ticket,
        ];
    }

    /**
     * @return array{status: string, message: string}
     */
    private static function error(string $message): array
    {
        return [
            'status' => 'error',
            'message' => $message,
        ];
    }
}
