<?php

namespace NextDeveloper\Support\Http\Transformers\AbstractTransformers;

use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;
use NextDeveloper\Support\Database\Models\Comments;

/**
 * Class CommentsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class AbstractCommentsTransformer extends AbstractTransformer
{

    /**
     * @param Comments $model
     *
     * @return array
     */
    public function transform(Comments $model)
    {
                        $iamAccountId = \NextDeveloper\IAM\Database\Models\Accounts::where('id', $model->iam_account_id)->first();
                    $iamUserId = \NextDeveloper\IAM\Database\Models\Users::where('id', $model->iam_user_id)->first();
                    $supportTicketId = \NextDeveloper\Support\Database\Models\Tickets::where('id', $model->support_ticket_id)->first();

        return $this->buildPayload(
            [
            'id'  =>  $model->uuid,
            'comment'  =>  $model->comment,
            'iam_account_id'  =>  $iamAccountId ? $iamAccountId->uuid : null,
            'iam_user_id'  =>  $iamUserId ? $iamUserId->uuid : null,
            'support_ticket_id'  =>  $supportTicketId ? $supportTicketId->uuid : null,
            'created_at'  =>  $model->created_at,
            'updated_at'  =>  $model->updated_at,
            'deleted_at'  =>  $model->deleted_at,
            ]
        );
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
