<?php

namespace NextDeveloper\Support\Actions\Tickets;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Support\Database\Models\Tickets;

/**
 * Removes the agent assignment from a support ticket (clears responsible_user_id).
 */
class UnassignTicket extends AbstractAction
{
    public const EVENTS = [
        'ticket-unassigned:NextDeveloper\Support\Tickets',
    ];

    public function __construct(Tickets $ticket, $params = null)
    {
        $this->model = $ticket;

        parent::__construct($params);
    }

    public function handle(): void
    {
        $this->setProgress(0, 'Unassigning ticket');

        UserHelper::runAsAdmin(function (): void {
            $this->model->update([
                'responsible_user_id' => null,
            ]);
        });

        CacheHelper::deleteKeys(Tickets::class, $this->model->uuid);

        $this->setProgress(90, 'Firing event');
        Events::fire('ticket-unassigned:NextDeveloper\Support\Tickets', $this->model->fresh());

        $this->setFinished('Ticket unassigned');
    }
}
