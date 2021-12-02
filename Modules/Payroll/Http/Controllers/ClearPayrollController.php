<?php

namespace Modules\Payroll\Http\Controllers;

use App\Helper\HTTPStatus;
use App\Http\Controllers\BaseResponseController;
use Illuminate\Support\Facades\Auth;
use Modules\Payroll\Entities\TempPayroll;
use Modules\Payroll\Entities\TempTransactionUpload;

class ClearPayrollController extends BaseResponseController
{

    public function clearCheckingListHalf()
    {
        $companyCode = @Auth::user()->company_code;
        TempPayroll::whereHas('contract', function ($query) use ($companyCode) {
            $query->whereRaw("contract_object->>'$.company.code'=?", $companyCode);
        })
//            ->where('created_by', $userId)
            ->where('transaction_code_id', TRANSACTION_CODE['HALF_SALARY'])
            ->delete();
        return $this->response(null, HTTPStatus::HTTP_SUCCESS, 'Data were cleared!');
    }

    public function clearCheckingListFull()
    {
        $user = Auth::user();
        $companyCode = @$user->company_code;

        TempTransactionUpload::whereHas('contract', function ($query) use ($companyCode) {
            $query->whereRaw("contract_object->>'$.company.code'=?", $companyCode);
        })
            ->delete();
        TempPayroll::whereHas('contract', function ($query) use ($companyCode) {
            $query->whereRaw("contract_object->>'$.company.code'=?", $companyCode);
        })
            ->where('transaction_code_id', '!=', TRANSACTION_CODE['HALF_SALARY'])
            ->delete();
        return $this->response(null, HTTPStatus::HTTP_SUCCESS, 'Data were cleared!');
    }
}
