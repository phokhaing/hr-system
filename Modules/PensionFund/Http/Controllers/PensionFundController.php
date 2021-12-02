<?php

namespace Modules\PensionFund\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Modules\PensionFund\Http\Requests\StorePFSettingRequest;
use Modules\PensionFund\Imports\PensionFundImport;
use Modules\PensionFund\Imports\PensionFundVerify;
use App\StaffInfoModel\StaffPersonalInfo;
use App\Contract;
use Modules\PensionFund\Entities\PensionFunds;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PensionFundController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:monthly_upload_pension_found');
        $this->middleware('permission:claim_pension_found');
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('pensionfund::index');
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function upload()
    {
        return view('pensionfund::upload_pension_fund.upload');
    }

    public function import(Request $request)
    {

        $x = $request->validate([
            'excel_file' => 'required|mimes:xls,xlsx',
        ]);

        $path1 = $request->file('excel_file')->store('temp');
        $path = storage_path('app') . '/' . $path1;
        // $save = Excel::import(new PensionFundImport, $path);

        $collection = Excel::toCollection(null, $path);
        $collectionFirstSheet = $collection[0]->forget(0)->map(function ($item) {
            DB::beginTransaction();
            try {
                $isSelected = !is_null(@$item[0]) && @$item[0] > 0;
                if($isSelected){
                    //Get column from upload excel file
                    $reportedDate = $this->transformDate($item[0]); //Equal pension fund by month
                    $systemId = $item[1]; //Equal Staff personal info id
                    $staffFullNameEn = $item[2];
                    $gender = $this->getGender($item[3]);
                    $location = $item[4];
                    $locationShortName = $item[5];
                    $dateOfEmployment = $this->transformDate($item[6]);
                    $effectiveDate = $this->transformDate($item[7]);
                    $grossBaseSalaryPerContract = $item[8];
                    $grossBaseSalary = $item[9];
                    $addition = $item[10];
                    $deduction = $item[11];
                    $acrBalanceStaff = $item[12];
                    $acrBalanceSkp = $item[13];
                    $balance = $item[14];

                    $staffPersonalInfo = $this->findStaffInCurrentHRSystem($systemId, $staffFullNameEn, $gender);
                        if($staffPersonalInfo != null){
                            // $contract = Contract::currentContract($staffPersonalInfo->id)->first();
                            $contract = Contract::where('staff_personal_info_id', $staffPersonalInfo->id)
                                ->whereRaw("TRIM(',' FROM contract_object->>'$.salary')", $grossBaseSalaryPerContract)
                                ->orderBy('start_date', 'DESC')
                                ->first();

                            //Validate to check staff not yet register in current HR System
                            if (is_null($contract)) {
                                throw new \Exception("This staff: " . $staffFullNameEn . "-" . $systemId . ", Doesn't register in HR System yet.");
                            }

                            $pfObj = [
                                "date_of_employment" => $dateOfEmployment,
                                "effective_date" => $effectiveDate,
                                "gross_base_salary_per_contract" => $grossBaseSalaryPerContract,
                                "gross_base_salary" => $grossBaseSalary,
                                "addition" => $addition,
                                "deduction" => $deduction,
                                "acr_balance_staff" => $acrBalanceStaff,
                                "acr_balance_skp" => $acrBalanceSkp,
                                "balance" => $balance,
                                "report_date" => $reportedDate,
                                "location" => $location,
                                "location_short_name" => $locationShortName
                            ];

                            PensionFunds::create([
                                'staff_personal_info_id' => @$staffPersonalInfo->id,
                                'contract_id' => @$contract->id,
                                'json_data' => $pfObj,
                                'created_by' => Auth::id(),
                            ]);
                        }
                }
                DB::commit();
                
            } catch (\Exception $ex) {
                DB::rollBack();
                dd("Please check these staffs below to make sure you have registered or system Id is not the same in HR system!". $ex->getMessage());
            }
        }); //Remove header and get first sheet and filter only records contain value

        return redirect()->route('pensionfund::upload.pf')->with(['success' => 1]);
    }

    /**
     * Transform a date value into a Carbon object.
     *
     * @return \Carbon\Carbon|null
     */
    public function transformDate($value, $format = 'd-m-Y')
    {
        try {
            return (\Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('d-m-Y'));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

    /**
     * @param $pensionFundCol (F => 1, M => 0)
     */
    private function getGender($pensionFundCol)
    {
        if (is_null($pensionFundCol) || $pensionFundCol == "M") {
            return "0";
        } else {
            return "1";
        }
    }

    private function findStaffInCurrentHRSystem($systemCode, $fullNameEn, $gender)
    {
        $staff = StaffPersonalInfo::where('staff_id', $systemCode)
            ->first();
        if (is_null($staff)) {
            $staff = StaffPersonalInfo::where(DB::raw("CONCAT(last_name_en, ' ', first_name_en)"), 'like', '%' . $fullNameEn . '%')
                ->where('gender', $gender)
                ->first();
        }

        return $staff;
    }

    public function verify(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $path1 = $request->file('file')->store('temp');
        $path = storage_path('app') . '/' . $path1;

        $collection = Excel::toCollection(null, $path);
        $collectionFirstSheet = $collection[0]->forget(0)->filter(function ($item) {
            $item[6] = $this->transformDate($item[6]);
            $item[7] = $this->transformDate($item[7]);
            $item[0] = $this->transformDate($item[0]);
            return !is_null(@$item[0]) && @$item[0] > 0;
        }); //Remove header and get first sheet and filter only records contain value
        return $collectionFirstSheet;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('pensionfund::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(StorePFSettingRequest $request)
    {
        // try {
        //     $data = [
        //         'json_data' => $request->except('_token'),
        //     ];
        //     $save = (new Categories())->createRecord($data);
        //     if ($save) {
        //         return redirect('/hrtraining/category-setting')->with(['success' => 1]);
        //     }
        // } catch (\Exception $e) {
        //     return $e;
        // }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('pensionfund::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('pensionfund::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
