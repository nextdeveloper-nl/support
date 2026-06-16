<?php

namespace NextDeveloper\Support\Http\Requests\AgentExpertises;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class AgentExpertisesUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'common_category_id' => 'nullable|exists:common_categories,uuid|uuid',
        'proficiency' => 'integer',
        'is_available' => 'boolean',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}