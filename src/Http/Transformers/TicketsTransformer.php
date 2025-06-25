<?php

namespace NextDeveloper\Support\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Support\Database\Models\Tickets;
use NextDeveloper\Support\Http\Transformers\AbstractTransformers\AbstractTicketsTransformer;

/**
 * Class TicketsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class TicketsTransformer extends AbstractTicketsTransformer
{

    /**
     * @param Tickets $model
     *
     * @return array
     */
    public function transform(Tickets $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Tickets', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Tickets', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
