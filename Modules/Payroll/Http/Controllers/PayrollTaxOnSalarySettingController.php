<?php

namespace Modules\Payroll\Http\Controllers;

use App\Helper\HTTPStatus;
use App\Http\Controllers\BaseResponseController;
use App\Validations\ValidateResponse;
use Illuminate\Http\Request;
use Modules\Payroll\Entities\SysTaxParams;
use Permissions;

class PayrollTaxOnSalarySettingController extends BaseResponseController
{
    private $validateResponse;

    public function __construct()
    {
        $this->middleware('permission:' . Permissions::ADD_TAX_ON_SALARY, ['only' => ['store']]);
        $this->middleware('permission:' . Permissions::UPDATE_TAX_ON_SALARY, ['only' => ['update']]);
        $this->validateResponse = new ValidateResponse();
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric',
            'salary_range_from' => 'required|numeric',
            'salary_range_to' => 'required|numeric'
        ]);
        $setting = SysTaxParams::find($id);
        $setting->updateRecord($setting->id, [
            "tax_object" => [
                'tax_rate' => @floatval(@$request->rate),
                'tax_range_from' => @floatval(@$request->salary_range_from),
                'tax_range_to' => @floatval(@$request->salary_range_to),
            ]
        ]);

        return redirect()->back()->with(['success' => 1]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric',
            'salary_range_from' => 'required|numeric',
            'salary_range_to' => 'required|numeric'
        ]);
        $setting = new SysTaxParams();
        $setting->createRecord([
            "tax_object" => [
                'tax_rate' => @floatval(@$request->rate),
                'tax_range_from' => @floatval(@$request->salary_range_from),
                'tax_range_to' => @floatval(@$request->salary_range_to),
            ]
        ]);

        return redirect()->back()->with(['success' => 1]);
    }

    public function delete(Request $request)
    {
        if (!$this->validateResponse->checkPermission(Permissions::DELETE_TAX_ON_SALARY)) {
            return $this->response(
                null,
                HTTPStatus::HTTP_AUTHORIZATION,
                'Sorry, you are not available to delete tax on salary!'
            );
        }

        $deleted = SysTaxParams::where('id', $request->id)->delete();
        return $this->response(null, @$deleted ? HTTPStatus::HTTP_SUCCESS : HTTPStatus::HTTP_FAIL);
    }
}
