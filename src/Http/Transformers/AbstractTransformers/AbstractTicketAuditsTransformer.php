<?php

namespace NextDeveloper\Support\Http\Transformers\AbstractTransformers;

use NextDeveloper\Support\Database\Models\TicketAudits;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

/**
 * Class TicketAuditsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class AbstractTicketAuditsTransformer extends AbstractTransformer
{

    /**
     * @param TicketAudits $model
     *
     * @return array
     */
    public function transform(TicketAudits $model)
    {
                        $iamUserId = \NextDeveloper\IAM\Database\Models\Users::where('id', $model->iam_user_id)->first();
        
        return $this->buildPayload(
            [
            'id'  =>  $model->uuid,
            'comments'  =>  $model->comments,
            'iam_user_id'  =>  $iamUserId ? $iamUserId->uuid : null,
            'point'  =>  $model->point,
            'created_at'  =>  $model->created_at,
            'updated_at'  =>  $model->updated_at,
            'deleted_at'  =>  $model->deleted_at,
            ]
        );
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
