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
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}