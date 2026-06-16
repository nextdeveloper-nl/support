<?php

namespace NextDeveloper\Support\Http\Requests\Csats;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class CsatsUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'support_ticket_id' => 'nullable|exists:support_tickets,uuid|uuid',
        'score' => 'nullable|integer',
        'comment' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}