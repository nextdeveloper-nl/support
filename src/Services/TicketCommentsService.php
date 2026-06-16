<?php

namespace NextDeveloper\Support\Services;

use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Support\Database\Models\TicketComments;
use NextDeveloper\Support\Database\Models\Tickets;
use NextDeveloper\Support\Services\AbstractServices\AbstractTicketCommentsService;

/**
 * This class is responsible from managing the data for TicketComments
 *
 * Class TicketCommentsService.
 *
 * @package NextDeveloper\Support\Database\Models
 */
class TicketCommentsService extends AbstractTicketCommentsService
{
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function create(array $data)
    {
        $comment = parent::create($data);

        self::stampFirstResponse($comment);

        return $comment;
    }

    /**
     * Stamps first_response_at on the ticket the first time a support agent posts a
     * public (non-internal) reply — the SLA first-response clock-stop.
     */
    private static function stampFirstResponse(TicketComments $comment): void
    {
        $ticket = Tickets::withoutGlobalScope(AuthorizationScope::class)
            ->find($comment->support_ticket_id);

        if (! $ticket || $ticket->first_response_at) {
            return;
        }

        if ($comment->is_internal || $comment->is_system_message) {
            return;
        }

        $isAgent = UserHelper::hasRole('support-admin') || UserHelper::hasRole('support-specialist');

        if (! $isAgent) {
            return;
        }

        UserHelper::runAsAdmin(function () use ($ticket): void {
            $ticket->update(['first_response_at' => now()]);
        });
    }
}