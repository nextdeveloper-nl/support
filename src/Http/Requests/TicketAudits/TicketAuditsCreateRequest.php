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
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}