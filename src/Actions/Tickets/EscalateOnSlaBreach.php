<?php

namespace NextDeveloper\Support\Actions\Tickets;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Support\Database\Models\Tickets;

/**
 * Escalates a ticket whose SLA has been breached: raises its priority (capped at 5)
 * and fires the sla-breached event so notification listeners can alert the team.
 * Dispatched by CheckTicketSlaBreachesJob, which sets the breach flags first.
 */
class EscalateOnSlaBreach extends AbstractAction
{
    public const EVENTS = [
        'sla-breached:NextDeveloper\Support\Tickets',
    ];

    public const PARAMS = [
        'type' => 'nullable|string',
    ];

    public function __construct(Tickets $ticket, $params = null)
    {
        $this->model = $ticket;

        parent::__construct($params);
    }

    public function handle(): void
    {
        $this->setProgress(0, 'Escalating ticket on SLA breach');

        $newPriority = min((int) $this->model->priority + 1, 5);

        UserHelper::runAsAdmin(function () use ($newPriority): void {
            $this->model->update([
                'priority' => $newPriority,
                'level' => min((int) $this->model->level + 1, 5),
            ]);
        });

        CacheHelper::deleteKeys(Tickets::class, $this->model->uuid);

        $this->setProgress(90, 'Firing event');
        Events::fire('sla-breached:NextDeveloper\Support\Tickets', $this->model->fresh());

        $this->setFinished('Ticket escalated to priority '.$newPriority.' on SLA breach');
    }
}
