<?php

namespace NextDeveloper\Support\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use League\Fractal\Resource\Item;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\IAM\Http\Transformers\PublicUsersTransformer;
use NextDeveloper\Support\Database\Models\TicketComments;
use NextDeveloper\Support\Http\Transformers\AbstractTransformers\AbstractTicketCommentsTransformer;

/**
 * Class TicketCommentsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class TicketCommentsTransformer extends AbstractTicketCommentsTransformer
{

    /**
     * @var array
     */
    protected array $availableIncludes = [
        'states',
        'actions',
        'media',
        'comments',
        'votes',
        'socialMedia',
        'phoneNumbers',
        'addresses',
        'meta',
        'user'
    ];

    /**
     * @param TicketComments $model
     *
     * @return array
     */
    public function transform(TicketComments $model)
    {
        $transformed = Cache::get(
            CacheHelper::getKey('TicketComments', $model->uuid, 'Transformed')
        );

        if($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);

        Cache::set(
            CacheHelper::getKey('TicketComments', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
    }

    /**
     * @param TicketComments $model
     *
     * @return Item|null
     */
    public function includeUser(TicketComments $model)
    {
        $user = Users::where('id', $model->iam_user_id)->first();

        if ($user) {
            return $this->item($user, new PublicUsersTransformer());
        }

        return null;
    }

}