<?php

namespace NextDeveloper\Support\Http\Transformers;

use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Commons\Database\Models\States;
use NextDeveloper\Commons\Helpers\ObjectHelper;
use NextDeveloper\Support\Helpers\TicketPayloads;
use NextDeveloper\Support\Helpers\TicketPeople;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Http\Transformers\PublicUsersTransformer;
use NextDeveloper\Support\Database\Models\TicketComments;
use NextDeveloper\Support\Database\Models\Tickets;
use NextDeveloper\Support\Http\Transformers\AbstractTransformers\AbstractTicketsTransformer;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

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
        return CacheHelper::rememberTransformed(
            'Tickets',
            $model->uuid,
            function () use ($model) {
                $transformed = parent::transform($model);

                //  The record the ticket is opened on is stored as a class and an internal id.
                $transformed['object_id'] = ObjectHelper::getObjectUuid($model->object_type, $model->object_id);

                //  The names of the people on it, as the perspective serves them (fixlean #736).
                $transformed += TicketPeople::names($model);

                //  What the application tells about that record (TicketPayloads).
                return TicketPayloads::decorate($transformed, $model);
            }
        );
    }
}
