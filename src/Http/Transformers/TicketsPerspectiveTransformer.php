<?php

namespace NextDeveloper\Support\Http\Transformers;

use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\Commons\Helpers\ObjectHelper;
use NextDeveloper\IAM\Database\Models\Accounts;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\Support\Database\Models\TicketsPerspective;
use NextDeveloper\Support\Http\Transformers\AbstractTransformers\AbstractTicketsPerspectiveTransformer;
use NextDeveloper\Support\Database\Models\Tickets;
use NextDeveloper\Commons\Database\Models\Media;
use NextDeveloper\Commons\Http\Transformers\MediaTransformer;

/**
 * Class TicketsPerspectiveTransformer. This class is being used to manipulate the data we are serving to the customer
 */
class TicketsPerspectiveTransformer extends AbstractTicketsPerspectiveTransformer
{
    /**
     * @return array
     */
    public function transform(TicketsPerspective $model)
    {
        return CacheHelper::rememberTransformed(
            'TicketsPerspective',
            $model->uuid,
            function () use ($model) {
                $transformed = $this->withSeekerAccount(parent::transform($model), $model);

                //  The record the ticket is opened on is stored as a class and an internal id.
                $transformed['object_id'] = ObjectHelper::getObjectUuid($model->object_type, $model->object_id);

                return $transformed;
            }
        );
    }

    /**
     * The generated transformer resolves the support seeker account inside the caller's
     * authorization scope, so an agent looking at a ticket filed for another account gets
     * null - which is exactly the account the panel needs in order to link through to
     * /crm/accounts/{uuid}. The ticket itself is already authorized by the time we
     * transform it, and support_seeker_name is served unscoped from the view anyway, so
     * resolving the uuid past the scope exposes nothing new.
     *
     * @param  array<string, mixed>  $transformed
     * @return array<string, mixed>
     */
    private function withSeekerAccount(array $transformed, TicketsPerspective $model): array
    {
        if (! $model->support_seeker_account_id || ($transformed['support_seeker_account_id'] ?? null)) {
            return $transformed;
        }

        $transformed['support_seeker_account_id'] = Accounts::withoutGlobalScope(AuthorizationScope::class)
            ->where('id', $model->support_seeker_account_id)
            ->value('uuid');

        return $transformed;
    }

    /**
     * The files of the ticket. They are attached to the base model, so the generated include, which
     * looked them up under the perspective's own class, never found any.
     */
    public function includeMedia(TicketsPerspective $model)
    {
        $media = Media::where('object_type', Tickets::class)
            ->where('object_id', $model->id)
            ->get();

        return $this->collection($media, new MediaTransformer());
    }
}
