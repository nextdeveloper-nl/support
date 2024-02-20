<?php

namespace NextDeveloper\Support\Http\Requests\Comments;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class CommentsUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'comment' => 'nullable|string',
        'support_ticket_id' => 'nullable|exists:support_tickets,uuid|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}