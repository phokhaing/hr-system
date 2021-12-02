<?php


namespace Modules\HRTraining\Http\Controllers;


use App\Http\Controllers\Controller;
use Modules\HRTraining\Entities\Resources\StaffTrainingResource;
use Modules\HRTraining\Entities\Trainees;

class UnitTestController extends Controller
{
    public function staffTrainingReport(){
        $trainees = Trainees::with([
            'staff',
            'contract',
            'enrollment'
        ])
            ->orderBy('id', 'desc')
            ->get();

        return StaffTrainingResource::collection($trainees);

    }

}