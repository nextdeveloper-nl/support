<?php

namespace NextDeveloper\Support\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Support\Database\Models\TicketsPerspective;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Support\Http\Transformers\AbstractTransformers\AbstractTicketsPerspectiveTransformer;

/**
 * Class TicketsPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class TicketsPerspectiveTransformer extends AbstractTicketsPerspectiveTransformer
{

    /**
     * @param TicketsPerspective $model
     *
     * @return array
     */
    public function transform(TicketsPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('TicketsPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('TicketsPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
