<?php

namespace NextDeveloper\Support\Http\Requests\TicketAudits;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TicketAuditsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'comments' => 'nullable|string',
        'point' => 'integer',
        'support_ticket_id' => 'nullable|exists:support_tickets,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}