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
            'title' => 'nullable|string',
        'description' => 'nullable|string',
        'tags' => '',
        'is_closed' => 'boolean',
        'level' => 'integer',
        'priority' => 'integer',
        'response_time' => 'nullable|date',
        'object_id' => 'nullable',
        'object_type' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}