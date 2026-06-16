<?php

namespace NextDeveloper\Support\Http\Requests\SlaPolicies;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class SlaPoliciesUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'nullable|string',
        'priority' => 'nullable|integer',
        'response_target_minutes' => 'nullable|integer',
        'resolution_target_minutes' => 'nullable|integer',
        'is_active' => 'boolean',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}