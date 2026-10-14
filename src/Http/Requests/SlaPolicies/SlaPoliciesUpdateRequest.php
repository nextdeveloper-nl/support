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
        'kind' => 'nullable|string|max:64',
        'conditions' => 'sometimes|array',
        'conditions.categories' => 'sometimes|array',
        'conditions.categories.*' => 'uuid',
        'conditions.priorities' => 'sometimes|array',
        'conditions.priorities.*' => 'integer',
        'conditions.assets' => 'sometimes|array',
        'conditions.assets.*' => 'uuid',
        'clock' => 'sometimes|string|in:7x24,workhours',
        'work_days' => 'sometimes|array',
        'work_days.*' => 'integer|between:1,7',
        'work_start' => 'nullable|required_if:clock,workhours|date_format:H:i',
        'work_end' => 'nullable|required_if:clock,workhours|date_format:H:i',
        'due_soon_percent' => 'sometimes|integer|between:1,100',
        'escalation' => 'sometimes|array',
        'escalation.*.after_minutes' => 'required_with:escalation|integer|min:0',
        'escalation.*.team_id' => 'nullable|uuid',
        'escalation.*.user_id' => 'nullable|uuid',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}