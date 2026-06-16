<?php

namespace NextDeveloper\Support\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Support\Database\Models\KbArticles;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Support\Http\Transformers\AbstractTransformers\AbstractKbArticlesTransformer;

/**
 * Class KbArticlesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class KbArticlesTransformer extends AbstractKbArticlesTransformer
{

    /**
     * @param KbArticles $model
     *
     * @return array
     */
    public function transform(KbArticles $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('KbArticles', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('KbArticles', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
