<?php

namespace NextDeveloper\Support\Http\Requests\KbArticlesPerspective;

use NextDeveloper\Commons\Http\Requests\AbstractFormRequest;

class KbArticlesPerspectiveCreateRequest extends AbstractFormRequest
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
        'is_published' => 'nullable|boolean',
        'view_count' => 'nullable|integer',
        'helpful_count' => 'nullable|integer',
        'not_helpful_count' => 'nullable|integer',
        'tags' => 'nullable|string',
        'category_name' => 'nullable|string',
        'author_name' => 'nullable|string',
        ];
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}