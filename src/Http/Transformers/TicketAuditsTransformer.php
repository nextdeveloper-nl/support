<?php

namespace NextDeveloper\Support\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Support\Database\Models\TicketAudits;
use NextDeveloper\Support\Http\Transformers\AbstractTransformers\AbstractTicketAuditsTransformer;

/**
 * Class TicketAuditsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class TicketAuditsTransformer extends AbstractTicketAuditsTransformer
{

    /**
     * @param TicketAudits $model
     *
     * @return array
     */
    public function transform(TicketAudits $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('TicketAudits', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('TicketAudits', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
