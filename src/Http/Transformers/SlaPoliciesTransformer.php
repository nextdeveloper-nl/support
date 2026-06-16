<?php

namespace NextDeveloper\Support\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Support\Database\Models\SlaPolicies;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Support\Http\Transformers\AbstractTransformers\AbstractSlaPoliciesTransformer;

/**
 * Class SlaPoliciesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class SlaPoliciesTransformer extends AbstractSlaPoliciesTransformer
{

    /**
     * @param SlaPolicies $model
     *
     * @return array
     */
    public function transform(SlaPolicies $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('SlaPolicies', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('SlaPolicies', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
