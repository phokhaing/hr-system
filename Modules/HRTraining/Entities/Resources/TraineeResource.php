<?php


namespace Modules\HRTraining\Entities\Resources;


use App\Entities\BaseResource;

class TraineeResource extends BaseResource
{
    public function toArray($request)
    {
        return [
            'enrollment_id' => @$this->enrollment_id,
            'request_join_status' => $this->request_join_status
        ];
    }

}