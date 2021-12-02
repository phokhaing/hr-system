<?php

namespace Modules\Payroll\Http\Controllers;

use App\Helper\HTTPStatus;
use App\Http\Controllers\BaseResponseController;
use App\Validations\ValidateResponse;
use Illuminate\Http\Request;
use Modules\Payroll\Entities\SysTaxParams;
use Modules\PensionFund\Entities\PensionFundRate;
use Permissions;

class PayrollPensionFundSettingController extends BaseResponseController
{
    private $validateResponse;

    public function __construct()
    {
        $this->middleware('permission:' . Permissions::ADD_PENSION_FUND_RATE_FROM_COMPANY, ['only' => ['store']]);
        $this->middleware('permission:' . Permissions::UPDATE_PENSION_FUND_RATE_FROM_COMPANY, ['only' => ['update']]);
        $this->validateResponse = new ValidateResponse();
    }

    public function update($id, Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric',
            'year_start' => 'required|numeric',
            'year_end' => 'required|numeric'
        ]);
        $setting = PensionFundRate::find($id);
        $setting->updateRecord($setting->id, [
            "json_data" => [
                'rate' => @floatval(@$request->rate),
                'year_start' => @floatval(@$request->year_start),
                'year_end' => @floatval(@$request->year_end),
            ]
        ]);

        return redirect()->back()->with(['success' => 1]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric',
            'year_start' => 'required|numeric',
            'year_end' => 'required|numeric'
        ]);
        $setting = new PensionFundRate();
        $setting->createRecord([
            "json_data" => [
                'rate' => @floatval(@$request->rate),
                'year_start' => @floatval(@$request->year_start),
                'year_end' => @floatval(@$request->year_end),
            ]
        ]);

        return redirect()->back()->with(['success' => 1]);
    }

    public function delete(Request $request)
    {
        if (!$this->validateResponse->checkPermission(Permissions::DELETE_PENSION_FUND_RATE_FROM_COMPANY)) {
            return $this->response(
                null,
                HTTPStatus::HTTP_AUTHORIZATION,
                'Sorry, you are not available to delete pension fund rate!'
            );
        }

        $deleted = PensionFundRate::where('id', $request->id)->delete();
        return $this->response(null, @$deleted ? HTTPStatus::HTTP_SUCCESS : HTTPStatus::HTTP_FAIL);
    }
}
