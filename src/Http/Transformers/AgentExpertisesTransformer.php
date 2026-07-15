<?php

namespace NextDeveloper\Support\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Support\Database\Models\AgentExpertises;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Support\Http\Transformers\AbstractTransformers\AbstractAgentExpertisesTransformer;

/**
 * Class AgentExpertisesTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class AgentExpertisesTransformer extends AbstractAgentExpertisesTransformer
{

    /**
     * @param AgentExpertises $model
     *
     * @return array
     */
    public function transform(AgentExpertises $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('AgentExpertises', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('AgentExpertises', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }
}
