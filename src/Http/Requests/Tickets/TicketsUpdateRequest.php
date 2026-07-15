<?php

namespace NextDeveloper\Support\Http\Requests\Tickets;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TicketsUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'nullable|string',
        'description' => 'nullable|string',
        'tags' => '',
        'is_public' => 'boolean',
        'priority' => 'nullable|integer',
        'level' => 'nullable|integer',
        'responsible_user_id' => 'nullable|exists:iam_users,uuid|uuid',
        'time_spent' => 'nullable|integer',
        'watcher_user_ids' => 'nullable',
        'watcher_account_ids' => 'nullable',
        'support_seeker_account_id' => 'nullable|exists:iam_accounts,uuid|uuid',
        'status' => 'string',
        'common_category_id' => 'nullable|exists:common_categories,uuid|uuid',
        'first_response_at' => 'nullable|date',
        'resolved_at' => 'nullable|date',
        'reopened_count' => 'integer',
        'is_first_contact_resolution' => 'boolean',
        'sla_resolution_due_at' => 'nullable|date',
        'sla_response_breached' => 'boolean',
        'sla_resolution_breached' => 'boolean',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}