<?php

namespace NextDeveloper\Support\Http\Requests\TicketComments;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TicketCommentsCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'comment' => 'required|string',
        'support_ticket_id' => 'required|exists:support_tickets,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}