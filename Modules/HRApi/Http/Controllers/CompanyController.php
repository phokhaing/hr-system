<?php

namespace Modules\HRApi\Http\Controllers;

use App\BranchesAndDepartments;
use App\Http\Resources\DepartmentBranchResource;
use App\Http\Resources\PositionResource;
use App\Position;
use Illuminate\Http\Request;

class CompanyController extends BaseApiController
{
    public function branchAndPositionList(Request $request)
    {
        if (!$this->checkHintUser($request->hint)) {
            return $this->myResponse(0, 'Sorry! ID card are not found.');    
        }
        
        $company_code =  $request->company_code;
        $positions = Position::where('company_code', $company_code)->get();
        $branchDepartments = BranchesAndDepartments::where('company_code', $company_code)->get();
        
        $positionsResources = PositionResource::collection($positions);
        $departmentBranchResourcecs = DepartmentBranchResource::collection($branchDepartments);
        $data = [
            'branch_departmens' => $departmentBranchResourcecs,
            'positions' => $positionsResources
        ];
        
        return $this->myResponse($data);
    }
}
