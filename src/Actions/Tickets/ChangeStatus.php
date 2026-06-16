<?php

namespace NextDeveloper\Support\Actions\Tickets;

use NextDeveloper\Commons\Actions\AbstractAction;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Events\Services\Events;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Support\Database\Models\TicketAudits;
use NextDeveloper\Support\Database\Models\Tickets;

/**
 * Moves a support ticket through its lifecycle (open -> pending -> waiting_on_customer
 * -> resolved -> closed) and keeps the derived columns consistent:
 *  - resolved/closed stamp resolved_at and is_closed
 *  - re-opening a resolved/closed ticket increments reopened_count and clears resolution
 *  - first-contact-resolution is flagged when a ticket is resolved without ever re-opening
 */
class ChangeStatus extends AbstractAction
{
    public const STATUSES = ['open', 'pending', 'waiting_on_customer', 'resolved', 'closed'];

    public const CLOSED_STATUSES = ['resolved', 'closed'];

    public const EVENTS = [
        'status-changed:NextDeveloper\Support\Tickets',
    ];

    public const PARAMS = [
        'status' => 'required|string',
    ];

    public function __construct(Tickets $ticket, $params = null)
    {
        $this->model = $ticket;

        parent::__construct($params);
    }

    public function handle(): void
    {
        $this->setProgress(0, 'Changing ticket status');

        $new = $this->params['status'];
        $old = $this->model->status;

        if (! in_array($new, self::STATUSES, true)) {
            $this->setFinishedWithError('Invalid status: '.$new);

            return;
        }

        if ($new === $old) {
            $this->setFinished('Ticket already in status '.$new);

            return;
        }

        $data = ['status' => $new];

        $wasClosed = in_array($old, self::CLOSED_STATUSES, true);
        $isClosing = in_array($new, self::CLOSED_STATUSES, true);

        if ($isClosing && ! $wasClosed) {
            $data['resolved_at'] = $this->model->resolved_at ?? now();
            $data['is_closed'] = $new === 'closed';
            $data['is_first_contact_resolution'] = (int) $this->model->reopened_count === 0;
        }

        if ($wasClosed && ! $isClosing) {
            $data['reopened_count'] = (int) $this->model->reopened_count + 1;
            $data['resolved_at'] = null;
            $data['is_closed'] = false;
            $data['is_first_contact_resolution'] = false;
        }

        if (! $isClosing && ! $wasClosed) {
            $data['is_closed'] = false;
        }

        $this->setProgress(50, 'Persisting status change');

        UserHelper::runAsAdmin(function () use ($data): void {
            $this->model->update($data);
        });

        $this->writeAudit($old, $new);

        CacheHelper::deleteKeys(Tickets::class, $this->model->uuid);

        $this->setProgress(90, 'Firing event');
        Events::fire('status-changed:NextDeveloper\Support\Tickets', $this->model->fresh());

        $this->setFinished('Ticket status changed from '.$old.' to '.$new);
    }

    private function writeAudit(string $old, string $new): void
    {
        try {
            UserHelper::runAsAdmin(function () use ($old, $new): void {
                TicketAudits::create([
                    'comments' => 'Status changed from '.$old.' to '.$new,
                    'iam_user_id' => UserHelper::me() ? UserHelper::me()->id : $this->model->iam_user_id,
                    'point' => 0,
                ]);
            });
        } catch (\Exception $e) {
            // Audit is best-effort; never block a status change because of it.
        }
    }
}
