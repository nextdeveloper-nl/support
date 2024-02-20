<?php

namespace NextDeveloper\Support\Http\Requests\Tests;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TestsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
        'result' => 'required|string',
        'data' => 'required',
        'is_passed' => 'boolean',
        'support_ticket_id' => 'required|exists:support_tickets,uuid|uuid',
        'common_action_id' => 'required|exists:common_actions,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}