<?php

namespace NextDeveloper\Support\Http\Transformers\AbstractTransformers;

use NextDeveloper\Support\Database\Models\Tests;
use NextDeveloper\Commons\Http\Transformers\AbstractTransformer;

/**
 * Class TestsTransformer. This class is being used to manipulate the data we are serving to the customer
 *
 * @package NextDeveloper\Support\Http\Transformers
 */
class AbstractTestsTransformer extends AbstractTransformer
{

    /**
     * @param Tests $model
     *
     * @return array
     */
    public function transform(Tests $model)
    {
                        $supportTicketId = \NextDeveloper\Support\Database\Models\Tickets::where('id', $model->support_ticket_id)->first();
                    $commonActionId = \NextDeveloper\Commons\Database\Models\Actions::where('id', $model->common_action_id)->first();
        
        return $this->buildPayload(
            [
            'id'  =>  $model->uuid,
            'name'  =>  $model->name,
            'result'  =>  $model->result,
            'data'  =>  $model->data,
            'is_passed'  =>  $model->is_passed,
            'support_ticket_id'  =>  $supportTicketId ? $supportTicketId->uuid : null,
            'common_action_id'  =>  $commonActionId ? $commonActionId->uuid : null,
            'created_at'  =>  $model->created_at,
            'updated_at'  =>  $model->updated_at,
            'deleted_at'  =>  $model->deleted_at,
            ]
        );
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
