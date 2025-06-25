<?php

namespace NextDeveloper\Support\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Commons\Database\Models\States;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Http\Transformers\PublicUsersTransformer;
use NextDeveloper\Support\Database\Models\TicketComments;
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
     * @var array
     */
    protected array $availableIncludes = [
        'states',
        'actions',
        'media',
        'ticketComments',
        'votes',
        'socialMedia',
        'phoneNumbers',
        'addresses',
        'meta',
        'user'
    ];

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

    /**
     * @param Tickets $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeTicketComments(Tickets $model)
    {
        $states = TicketComments::where('support_ticket_id', $model->id)
            ->get();

        return $this->collection($states, new TicketCommentsTransformer());
    }


    public function includeUser(Tickets $model)
    {
        $iamUserId = Users::where('id', $model->iam_user_id)->first();

        return $this->item($iamUserId, new PublicUsersTransformer());
    }
}
