<?php

namespace NextDeveloper\Support\Services;

use NextDeveloper\Commons\Helpers\DatabaseHelper;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\IAM\Helpers\UserHelper;
use NextDeveloper\Support\Actions\Tickets\AutoRouteTicket;
use NextDeveloper\Support\Database\Models\SlaPolicies;
use NextDeveloper\Support\Database\Models\Tickets;
use NextDeveloper\Support\Services\AbstractServices\AbstractTicketsService;

/**
 * This class is responsible from managing the data for Tickets
 *
 * Class TicketsService.
 *
 * @package NextDeveloper\Support\Database\Models
 */
class TicketsService extends AbstractTicketsService
{
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    public static function update($id, array $data)
    {
        // time_spent is calculated automatically; never accept it from the client.
        if (array_key_exists('time_spent', $data)) {
            unset($data['time_spent']);
        }

        $data = self::normalizeCategory($data);

        return parent::update($id, $data);
    }

    public static function create(array $data)
    {
        if (! array_key_exists('support_seeker_account_id', $data)) {
            $data['support_seeker_account_id'] = UserHelper::currentAccount()->id;
        }

        $data = self::normalizeCategory($data);
        $data = self::applySlaDueDates($data);

        $ticket = parent::create($data);

        if ($ticket->common_category_id) {
            AutoRouteTicket::dispatch($ticket);
        }

        return $ticket;
    }

    /**
     * Stamps response/resolution SLA due-dates from the active policy matching the
     * ticket priority. response_time = first-response deadline; sla_resolution_due_at
     * = resolution deadline.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function applySlaDueDates(array $data): array
    {
        $priority = (int) ($data['priority'] ?? 1) ?: 1;

        $policy = SlaPolicies::withoutGlobalScope(AuthorizationScope::class)
            ->where('priority', $priority)
            ->where('is_active', true)
            ->whereNull('iam_account_id')
            ->whereNull('deleted_at')
            ->first();

        if (! $policy) {
            return $data;
        }

        if (! array_key_exists('response_time', $data) || ! $data['response_time']) {
            $data['response_time'] = now()->addMinutes($policy->response_target_minutes);
        }

        if (! array_key_exists('sla_resolution_due_at', $data) || ! $data['sla_resolution_due_at']) {
            $data['sla_resolution_due_at'] = now()->addMinutes($policy->resolution_target_minutes);
        }

        return $data;
    }

    /**
     * Converts a category uuid from the API to the integer FK the column expects.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function normalizeCategory(array $data): array
    {
        if (array_key_exists('common_category_id', $data)
            && $data['common_category_id']
            && ! is_numeric($data['common_category_id'])) {
            $data['common_category_id'] = DatabaseHelper::uuidToId(
                '\NextDeveloper\Commons\Database\Models\Categories',
                $data['common_category_id']
            );
        }

        return $data;
    }
}