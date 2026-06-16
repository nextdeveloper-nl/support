<?php

namespace NextDeveloper\Support\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Support\Actions\Tickets\EscalateOnSlaBreach;
use NextDeveloper\Support\Database\Models\Tickets;

/**
 * Detects tickets that have breached their response or resolution SLA and escalates
 * them. response_time is the first-response deadline (breached when no first_response_at
 * by then); sla_resolution_due_at is the resolution deadline. Breach flags prevent
 * re-escalating the same ticket on every run. Intended to be dispatched on a schedule.
 */
class CheckTicketSlaBreachesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public const QUEUE_NAME = 'support';

    public const OPEN_STATUSES = ['open', 'pending', 'waiting_on_customer'];

    public int $tries = 3;

    public int $timeout = 120;

    public function __construct()
    {
        $this->queue = self::QUEUE_NAME;
    }

    public function handle(): void
    {
        $tickets = Tickets::withoutGlobalScopes()
            ->whereNull('deleted_at')
            ->whereNotIn('status', ['resolved', 'closed'])
            ->where(function ($q): void {
                $q->where(function ($r): void {
                    $r->whereNotNull('response_time')
                        ->where('response_time', '<', now())
                        ->whereNull('first_response_at')
                        ->where('sla_response_breached', false);
                })->orWhere(function ($r): void {
                    $r->whereNotNull('sla_resolution_due_at')
                        ->where('sla_resolution_due_at', '<', now())
                        ->where('sla_resolution_breached', false);
                });
            })
            ->get();

        foreach ($tickets as $ticket) {
            $flags = [];

            if ($ticket->response_time && $ticket->response_time->isPast()
                && ! $ticket->first_response_at && ! $ticket->sla_response_breached) {
                $flags['sla_response_breached'] = true;
            }

            if ($ticket->sla_resolution_due_at && $ticket->sla_resolution_due_at->isPast()
                && ! $ticket->sla_resolution_breached) {
                $flags['sla_resolution_breached'] = true;
            }

            if (empty($flags)) {
                continue;
            }

            UserHelper::runAsAdmin(function () use ($ticket, $flags): void {
                $ticket->update($flags);
            });

            $type = array_key_exists('sla_resolution_breached', $flags) ? 'resolution' : 'response';

            EscalateOnSlaBreach::dispatch($ticket->fresh(), ['type' => $type]);
        }
    }
}
