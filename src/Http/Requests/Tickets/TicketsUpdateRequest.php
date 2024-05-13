<?php

namespace NextDeveloper\Support\Http\Requests\Tickets;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class TicketsUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'nullable|string',
        'description' => 'nullable|string',
        'tags' => '',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}