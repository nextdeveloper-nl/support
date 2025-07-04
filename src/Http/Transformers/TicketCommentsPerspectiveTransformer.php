<?php

namespace NextDeveloper\Support\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Support\Database\Models\TicketCommentsPerspective;
use NextDeveloper\Support\Http\Transformers\AbstractTransformers\AbstractTicketCommentsPerspectiveTransformer;

/**
 * Class TicketCommentsPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class TicketCommentsPerspectiveTransformer extends AbstractTicketCommentsPerspectiveTransformer
{

    /**
     * @param TicketCommentsPerspective $model
     *
     * @return array
     */
    public function transform(TicketCommentsPerspective $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('TicketCommentsPerspective', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('TicketCommentsPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
