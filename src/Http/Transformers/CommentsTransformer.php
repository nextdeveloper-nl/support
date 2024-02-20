<?php

namespace NextDeveloper\Support\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Support\Database\Models\Comments;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Support\Http\Transformers\AbstractTransformers\AbstractCommentsTransformer;

/**
 * Class CommentsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class CommentsTransformer extends AbstractCommentsTransformer
{

    /**
     * @param Comments $model
     *
     * @return array
     */
    public function transform(Comments $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('Comments', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('Comments', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
