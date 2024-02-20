<?php

namespace NextDeveloper\Support\Http\Transformers\AbstractTransformers;

use NextDeveloper\Support\Database\Models\Tickets;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

/**
 * Class TicketsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class AbstractTicketsTransformer extends AbstractTransformer
{

    /**
     * @param Tickets $model
     *
     * @return array
     */
    public function transform(Tickets $model)
    {
                        $iamAccountId = \NextDeveloper\IAM\Database\Models\Accounts::where('id', $model->iam_account_id)->first();
                    $iamUserId = \NextDeveloper\IAM\Database\Models\Users::where('id', $model->iam_user_id)->first();
        
        return $this->buildPayload(
            [
            'id'  =>  $model->uuid,
            'title'  =>  $model->title,
            'description'  =>  $model->description,
            'iam_account_id'  =>  $iamAccountId ? $iamAccountId->uuid : null,
            'iam_user_id'  =>  $iamUserId ? $iamUserId->uuid : null,
            'tags'  =>  $model->tags,
            'is_closed'  =>  $model->is_closed,
            'level'  =>  $model->level,
            'priority'  =>  $model->priority,
            'response_time'  =>  $model->response_time,
            'object_id'  =>  $model->object_id,
            'object_type'  =>  $model->object_type,
            'created_at'  =>  $model->created_at,
            'updated_at'  =>  $model->updated_at,
            'deleted_at'  =>  $model->deleted_at,
            ]
        );
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
