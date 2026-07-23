<?php

namespace NextDeveloper\Support\Services;

use NextDeveloper\Commons\Helpers\DatabaseHelper;
use NextDeveloper\IAM\Database\Models\Accounts;
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
 */
class TicketsService extends AbstractTicketsService
{
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

    /**
     * Privileged ticket write used by lifecycle Actions (status/assignment/SLA/routing).
     *
     * These Actions have already authorized the operation at the action level and often run
     * in contexts that cannot pass the model's per-record policy: the elevated admin lacks
     * support ops, and SLA/routing jobs run with no current user at all. bypassRolesCheck
     * short-circuits the TicketsObserver policy while the observer's domain events still fire
     * (so NATS/live-sync keep working); runAsAdmin gives observer listeners a valid identity.
     *
     * @param  array<string, mixed>  $data
     */
    public static function privilegedUpdate(Tickets $ticket, array $data): void
    {
        UserHelper::runAsAdmin(function () use ($ticket, $data): void {
            UserHelper::bypassRolesCheck(true);

            try {
                $ticket->update($data);
            } finally {
                UserHelper::bypassRolesCheck(false);
            }
        });
    }

    public static function update($id, array $data)
    {
        // time_spent is calculated automatically; never accept it from the client.
        if (array_key_exists('time_spent', $data)) {
            unset($data['time_spent']);
        }

        $data = self::normalizeCategory($data);

        $ticket = parent::update($id, $data);

        // A tag can be added after creation too (e.g. an agent re-tags an existing
        // ticket as a bug report) - the job self-guards on tag presence and on
        // already having filed, so it's safe to dispatch unconditionally here as
        // well, mirroring create() below.
        \App\Jobs\Support\CreateGithubIssueForCustomerBugReportJob::dispatch($ticket);

        return $ticket;
    }

    public static function create(array $data)
    {
        $data = self::resolveSeekerAccount($data);
        $data = self::normalizeCategory($data);
        $data = self::applySlaDueDates($data);

        $ticket = parent::create($data);

        if ($ticket->common_category_id) {
            AutoRouteTicket::dispatch($ticket);
        }

        // Customer bug reports (tagged with config('support.bug_report_tag') in the
        // main app) get auto-filed as a GitHub issue.
        \App\Jobs\Support\CreateGithubIssueForCustomerBugReportJob::dispatch($ticket);

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
     * Resolves which account the ticket is for. Support staff (admin/specialist) may file on
     * behalf of any account (support_seeker_account_id, uuid → id). Everyone else — and the
     * default — is forced to their own account, so a customer cannot file for someone else
     * even by tampering with the payload.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private static function resolveSeekerAccount(array $data): array
    {
        $isAgent = UserHelper::hasRole('support-admin') || UserHelper::hasRole('support-specialist');
        $seeker = $data['support_seeker_account_id'] ?? null;

        if ($isAgent && $seeker) {
            if (! is_numeric($seeker)) {
                //  Resolve without the authorization scope: an agent files on behalf of any
                //  account, which is (by design) outside their own account's visibility.
                $seeker = Accounts::withoutGlobalScope(AuthorizationScope::class)
                    ->where('uuid', $seeker)
                    ->value('id');
            }

            if ($seeker) {
                $data['support_seeker_account_id'] = $seeker;

                return $data;
            }
        }

        $data['support_seeker_account_id'] = UserHelper::currentAccount()->id;

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
