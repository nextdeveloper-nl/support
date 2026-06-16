<?php

namespace NextDeveloper\Support\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Support\Database\Models\KbArticlesPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Support\Http\Transformers\AbstractTransformers\AbstractKbArticlesPerspectiveTransformer;

/**
 * Class KbArticlesPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class KbArticlesPerspectiveTransformer extends AbstractKbArticlesPerspectiveTransformer
{

    /**
     * @param KbArticlesPerspective $model
     *
     * @return array
     */
    public function transform(KbArticlesPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('KbArticlesPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('KbArticlesPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
