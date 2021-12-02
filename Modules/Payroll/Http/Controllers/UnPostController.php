<?php


namespace Modules\Payroll\Http\Controllers;

use App\Helper\HTTPStatus;
use App\Http\Controllers\BaseResponseController;
use App\Http\Controllers\Controller;
use App\Validations\ValidateResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Payroll\Entities\Payroll;
use Modules\Payroll\Entities\TempPayroll;
use Modules\Payroll\Transformers\PayrollFullMonthResource;
use Permissions;

class UnPostController extends BaseResponseController
{
    private $validateResponse;

    public function __construct()
    {
        $this->middleware('auth');
        $this->validateResponse = new ValidateResponse();
    }

    public function viewPostedList(Request $request)
    {
        return view('payroll::index');
    }

    public function viewPostedPayrollApi(Request $request)
    {
        if (!$this->validateResponse->checkPermission(Permissions::VIEW_POSTED_PAYROLL_FULL_MONTH)) {
            return $this->response(
                null,
                HTTPStatus::HTTP_AUTHORIZATION,
                'Sorry, you are not available to view posted payroll!'
            );
        }

        $transaction = new Payroll();
        $collection = $transaction->payrollReport(
            $request->company_code,
            $request->branch_department_code,
            $request->staff_personal_info_id,
            date('Y-m-d'),
            false,
            $request->keyword
        );

        return $this->response(
            PayrollFullMonthResource::collection(collect($collection)),
            HTTPStatus::HTTP_SUCCESS,
            'Success'
        );
    }

    public function unPostedPayroll(Request $request)
    {
        if (!$this->validateResponse->checkPermission(Permissions::UN_POST_PAYROLL_FULL_MONTH)) {
            return $this->response(
                null,
                HTTPStatus::HTTP_AUTHORIZATION,
                'Sorry, you are not available to view un post payroll!'
            );
        }

        DB::beginTransaction();
        try {
            $currentDate = date('Y-m-d');
            $transaction = Payroll::search(
                @$request->company_code,
                @$request->branch_department_code,
                $currentDate,
                @$request->staff_personal_info_id
            )
            ->where('transaction_code_id', '!=', TRANSACTION_CODE['HALF_SALARY'])
            ->delete();
            DB::commit();

            if (@$transaction) {
                return $this->response(
                    null,
                    HTTPStatus::HTTP_SUCCESS,
                    'Successfully un-posted payroll!'
                );
            } else {
                return $this->response(
                    null,
                    HTTPStatus::HTTP_FAIL,
                    'Up Posted Payroll is empty!'
                );
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->response(
                null,
                HTTPStatus::HTTP_FAIL,
                $exception->getMessage()
            );
        }
    }

    public function blockOrUnBlockTempTransactionApi(Request $request)
    {
        $item = $request->get('item');
        if ($request->is_block_temp_transactin) {
            $tempTransaction =  TempPayroll::find(@$item['transaction_id']);
        } else {
            $tempTransaction =  Payroll::find(@$item['transaction_id']);
        }

        if (@$tempTransaction) {
            $tempTransaction->updateRecord(@$item['transaction_id'], [
                'transaction_object->is_block' => !@$tempTransaction->transaction_object->is_block
            ]);
        }
        return $this->response(
            null,
            HTTPStatus::HTTP_SUCCESS,
            'Successfully block payroll!'
        );
    }
}
