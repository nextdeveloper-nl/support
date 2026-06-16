<?php

namespace NextDeveloper\Support\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Support\Database\Models\Csats;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Support\Http\Transformers\AbstractTransformers\AbstractCsatsTransformer;

/**
 * Class CsatsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class CsatsTransformer extends AbstractCsatsTransformer
{

    /**
     * @param Csats $model
     *
     * @return array
     */
    public function transform(Csats $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Csats', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Csats', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
