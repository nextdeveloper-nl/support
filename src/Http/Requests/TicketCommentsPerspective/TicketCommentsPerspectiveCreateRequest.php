<?php

namespace NextDeveloper\Support\Http\Requests\TicketCommentsPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TicketCommentsPerspectiveCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'comment' => 'nullable|string',
        'fullname' => 'nullable|string',
        'email' => 'nullable|string',
        'phone_number' => 'nullable|string',
        'pronoun' => 'nullable|string',
        'name' => 'nullable|string',
        'iam_account_type_id' => 'nullable|exists:iam_account_types,uuid|uuid',
        'support_ticket_id' => 'nullable|exists:support_tickets,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}