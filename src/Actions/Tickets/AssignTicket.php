<?php

namespace NextDeveloper\Support\Actions\Tickets;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Support\Database\Models\Tickets;

/**
 * Assigns a support ticket to an agent by writing responsible_user_id.
 * The ticket-assigned event is fired so notification listeners/pushers can inform the agent.
 */
class AssignTicket extends AbstractAction
{
    public const EVENTS = [
        'ticket-assigned:NextDeveloper\Support\Tickets',
    ];

    public const PARAMS = [
        'iam_user_id' => 'required|string',
    ];

    public function __construct(Tickets $ticket, $params = null)
    {
        $this->model = $ticket;

        parent::__construct($params);
    }

    public function handle(): void
    {
        $this->setProgress(0, 'Assigning ticket');

        $agent = UserHelper::getWithId($this->params['iam_user_id']);

        if (! $agent) {
            $this->setFinishedWithError('The specified agent could not be found.');

            return;
        }

        $this->setProgress(50, 'Saving assignment');

        UserHelper::runAsAdmin(function () use ($agent): void {
            $this->model->update([
                'responsible_user_id' => $agent->id,
            ]);
        });

        CacheHelper::deleteKeys(Tickets::class, $this->model->uuid);

        $this->setProgress(90, 'Firing event');
        Events::fire('ticket-assigned:NextDeveloper\Support\Tickets', $this->model->fresh());

        $this->setFinished('Ticket assigned to '.$agent->fullname);
    }
}
