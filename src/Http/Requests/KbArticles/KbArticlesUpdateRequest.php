<?php

namespace NextDeveloper\Support\Http\Requests\KbArticles;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class KbArticlesUpdateRequest extends AbstractFormRequest
{

    /**
     * @return array
     */
    public function rules()
    {
        return [
            'common_category_id' => 'nullable|exists:common_categories,uuid|uuid',
        'title' => 'nullable|string',
        'slug' => 'nullable|string',
        'body' => 'nullable|string',
        'excerpt' => 'nullable|string',
        'is_published' => 'boolean',
        'view_count' => 'integer',
        'helpful_count' => 'integer',
        'not_helpful_count' => 'integer',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}