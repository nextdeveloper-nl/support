<?php

namespace NextDeveloper\Support\Http\Requests\SlaPolicies;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class SlaPoliciesCreateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
        'priority' => 'required|integer',
        'response_target_minutes' => 'required|integer',
        'resolution_target_minutes' => 'required|integer',
        'is_active' => 'boolean',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}