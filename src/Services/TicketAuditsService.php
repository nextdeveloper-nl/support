<?php

namespace NextDeveloper\Support\Services;

use Illuminate\Http\Exceptions\HttpResponseException;
use NextDeveloper\Commons\Common\Enums\GenericErrorCodes;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Support\Database\Models\Tickets;
use NextDeveloper\Support\Services\AbstractServices\AbstractTicketAuditsService;

/**
 * This class is responsible from managing the data for TicketAudits
 *
 * Class TicketAuditsService.
 *
 * @package NextDeveloper\Support\Database\Models
 */
class TicketAuditsService extends AbstractTicketAuditsService
{

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    /**
     * An audit (fixlean: a score, `point`) belongs to a ticket the caller can see. The API names
     * the ticket by uuid; the column holds its id. The generated create passed the uuid straight
     * into the bigint column and wrote nothing.
     */
    public static function create(array $data)
    {
        return parent::create(self::resolveTicket($data, true));
    }

    public static function update($id, array $data)
    {
        return parent::update($id, self::resolveTicket($data, false));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function resolveTicket(array $data, bool $isCreate): array
    {
        if (array_key_exists('support_ticket_id', $data) && $data['support_ticket_id'] !== null && ! is_int($data['support_ticket_id'])) {
            $ticket = Tickets::where('uuid', $data['support_ticket_id'])->first();

            if (! $ticket) {
                throw new HttpResponseException(response()->json([
                    'message' => 'Validation failed. Please fix the values you are providing and try again.',
                    'code' => GenericErrorCodes::VALIDATION_FAILED,
                    'errors' => ['support_ticket_id' => ['support_ticket_id must be the id of a ticket you can see.']],
                ], 422));
            }

            $data['support_ticket_id'] = $ticket->id;

            //  The audit lives in the ticket's account, which is what the authorization scope reads.
            if ($isCreate && ! array_key_exists('iam_account_id', $data)) {
                $data['iam_account_id'] = $ticket->iam_account_id;
            }
        }

        if ($isCreate && ! array_key_exists('iam_account_id', $data)) {
            $data['iam_account_id'] = UserHelper::currentAccount()?->id;
        }

        return $data;
    }
}
