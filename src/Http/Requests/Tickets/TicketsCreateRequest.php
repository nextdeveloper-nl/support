<?php

namespace NextDeveloper\Support\Http\Requests\Tickets;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TicketsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
        'description' => 'nullable|string',
        'tags' => '',
        'is_public' => 'boolean',
        'responsible_user_id' => 'nullable|exists:iam_users,uuid|uuid',
        'time_spent' => 'integer',
        'watcher_user_ids' => 'nullable',
        'watcher_account_ids' => 'nullable',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}