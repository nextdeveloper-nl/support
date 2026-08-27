<?php

namespace NextDeveloper\Support\Http\Transformers;

use Illuminate\Support\Facades\Cache;
use NextDeveloper\Commons\Common\Cache\CacheHelper;
use NextDeveloper\IAM\Database\Models\Accounts;
use NextDeveloper\IAM\Database\Scopes\AuthorizationScope;
use NextDeveloper\Support\Database\Models\TicketsPerspective;
use NextDeveloper\Support\Http\Transformers\AbstractTransformers\AbstractTicketsPerspectiveTransformer;

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
        $transformed = Cache::get(
            CacheHelper::getKey('TicketsPerspective', $model->uuid, 'Transformed')
        );

        if ($transformed) {
            return $transformed;
        }

        $transformed = parent::transform($model);
        $transformed = $this->withSeekerAccount($transformed, $model);

        Cache::set(
            CacheHelper::getKey('TicketsPerspective', $model->uuid, 'Transformed'),
            $transformed
        );

        return $transformed;
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
}
