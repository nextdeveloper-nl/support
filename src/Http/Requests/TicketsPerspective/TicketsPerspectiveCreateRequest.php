<?php

namespace NextDeveloper\Support\Http\Requests\TicketsPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TicketsPerspectiveCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'nullable|string',
        'description' => 'nullable|string',
        'tags' => 'nullable',
        'is_closed' => 'nullable|boolean',
        'is_public' => 'nullable|boolean',
        'level' => 'nullable|integer',
        'priority' => 'nullable|integer',
        'status' => 'nullable|string',
        'common_category_id' => 'nullable|exists:common_categories,uuid|uuid',
        'response_time' => 'nullable|date',
        'first_response_at' => 'nullable|date',
        'resolved_at' => 'nullable|date',
        'sla_resolution_due_at' => 'nullable|date',
        'sla_response_breached' => 'nullable|boolean',
        'sla_resolution_breached' => 'nullable|boolean',
        'reopened_count' => 'nullable|integer',
        'is_first_contact_resolution' => 'nullable|boolean',
        'object_id' => 'nullable',
        'object_type' => 'nullable|string',
        'responsible_user_id' => 'nullable|exists:iam_users,uuid|uuid',
        'time_spent' => 'nullable|integer',
        'watcher_user_ids' => 'nullable',
        'watcher_account_ids' => 'nullable',
        'support_seeker_account_id' => 'nullable|exists:support_seeker_accounts,uuid|uuid',
        'fullname' => 'nullable|string',
        'email' => 'nullable|string',
        'phone_number' => 'nullable|string',
        'pronoun' => 'nullable|string',
        'name' => 'nullable|string',
        'iam_account_type_id' => 'nullable|exists:iam_account_types,uuid|uuid',
        'support_seeker_name' => 'nullable|string',
        'responsible_name' => 'nullable|string',
        'category_name' => 'nullable|string',
        'csat_score' => 'nullable|integer',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}