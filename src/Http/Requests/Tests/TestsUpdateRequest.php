<?php

namespace NextDeveloper\Support\Http\Requests\Tests;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TestsUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
        'result' => 'nullable|string',
        'data' => 'nullable',
        'is_passed' => 'boolean',
        'support_ticket_id' => 'nullable|exists:support_tickets,uuid|uuid',
        'common_action_id' => 'nullable|exists:common_actions,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}