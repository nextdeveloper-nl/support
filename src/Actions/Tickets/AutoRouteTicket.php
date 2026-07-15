<?php

namespace NextDeveloper\Support\Actions\Tickets;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\Support\Database\Models\AgentExpertises;
use NextDeveloper\Support\Database\Models\Tickets;
use NextDeveloper\Support\Services\TicketsService;

/**
 * Skill-based routing: assigns a newly created ticket to the most suitable agent for
 * its category — highest proficiency, currently available, and least loaded with open
 * tickets. Does nothing if the ticket is already assigned or has no category.
 */
class AutoRouteTicket extends AbstractAction
{
    public const EVENTS = [
        'ticket-routed:NextDeveloper\Support\Tickets',
    ];

    public const OPEN_STATUSES = ['open', 'pending', 'waiting_on_customer'];

    public function __construct(Tickets $ticket, $params = null)
    {
        $this->model = $ticket;

        parent::__construct($params);
    }

    public function handle(): void
    {
        $this->setProgress(0, 'Routing ticket to a skilled agent');

        if ($this->model->responsible_user_id) {
            $this->setFinished('Ticket already assigned; skipping auto-route.');

            return;
        }

        if (! $this->model->common_category_id) {
            $this->setFinished('Ticket has no category; skipping auto-route.');

            return;
        }

        $candidates = AgentExpertises::withoutGlobalScope(AuthorizationScope::class)
            ->where('common_category_id', $this->model->common_category_id)
            ->where('is_available', true)
            ->whereNull('deleted_at')
            ->orderByDesc('proficiency')
            ->get();

        if ($candidates->isEmpty()) {
            $this->setFinished('No available agent with matching expertise; left unassigned.');

            return;
        }

        $this->setProgress(50, 'Selecting least-loaded agent');

        $best = $candidates->sortBy(fn ($e) => self::openLoad($e->iam_user_id))->first();

        TicketsService::privilegedUpdate($this->model, [
            'responsible_user_id' => $best->iam_user_id,
        ]);

        CacheHelper::deleteKeys(Tickets::class, $this->model->uuid);

        $this->setProgress(90, 'Firing event');
        Events::fire('ticket-routed:NextDeveloper\Support\Tickets', $this->model->fresh());

        $this->setFinished('Ticket routed to agent #'.$best->iam_user_id);
    }

    private static function openLoad(int $agentId): int
    {
        return Tickets::withoutGlobalScope(AuthorizationScope::class)
            ->where('responsible_user_id', $agentId)
            ->whereIn('status', self::OPEN_STATUSES)
            ->whereNull('deleted_at')
            ->count();
    }
}
