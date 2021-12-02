<?php

namespace App\Http\Controllers;

use App\BranchesAndDepartments;
use App\Company;
use App\Contract;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\DepartmentBranchResource;
use App\Http\Resources\PositionResource;
use App\Http\Resources\TransferWorkToStaffResource;
use App\NewSalary;
use App\Position;
use App\StaffInfoModel\StaffPersonalInfo;
use Google\Service\CloudHealthcare\Message;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ContractController extends Controller
{
    /**
     * ContractController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_contract');
        $this->middleware('permission:add_contract', ['only' => ['create', 'storeContractByType']]);
        $this->middleware('permission:edit_contract', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_contract', ['only' => ['delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $staff_profiles = (new Contract())->getStaffProfile();

        $companies = Company::getCompanyDependOnUser()->get();
        $branchesDepartments = BranchesAndDepartments::getDependOnUser()->get();
        $positions = Position::getDependOnUser()->get();
        $managers = Contract::getManager()->get();

        $currency = CURRENCY;
        $contract_type = CONTRACT_TYPE;
        $reasons = DB::table('reason_company_note')->get();
        // Convert request array filter to query string
        $query_string = http_build_query($request->all());
        // Convert query string to array filter
        parse_str($query_string, $query_array);
        $_keyword = isset($query_array["key_word"]) ? strtolower($query_array["key_word"]) : '';
        $_company_code = @$query_array["company_code"];
        $_branchesDepartments = @$query_array["branch_department_code"] ?: null;
        $_position = isset($query_array["position_code"]) ?: null;
        $_contract_type = @$query_array["contract_type"] ?: null;
        $_contract_start_date = isset($query_array["start_date"]) ? date('Y-m-d', strtotime($query_array["start_date"] . ' 00:00:00')) : '';
        $_contract_end_date = isset($query_array["end_date"]) ? date('Y-m-d', strtotime($query_array["end_date"] . ' 00:00:00')) : '';
        $contracts = collect();
        if ($request->input('_token') != null) {
            $query = (new Contract())->advanceSearch($_keyword, $_company_code, $_branchesDepartments, $_position, $_contract_type, $_contract_start_date, $_contract_end_date);
            $contracts = $query->orderBy('id', 'DESC')->paginate(PER_PAGE);
        }

        /**
         * From Create new Staff Personal Info and Redirect here to Create New Contract
         */
        $isNewContract = $request->get('is_new_contract');
        $newStaffId = $request->get('staff_id');
        $newStaffPersonalInfo = null;
        if ($isNewContract && !empty($newStaffId)) {
            $staffId = decrypt($newStaffId);
            $newStaffPersonalInfo = StaffPersonalInfo::find($staffId);
        }

        return view('contract.index', compact(
            'companies', 'positions', 'currency', 'branchesDepartments', 'managers', 'staff_profiles',
            'contract_type', 'contracts', 'reasons', 'isNewContract', 'newStaffPersonalInfo'
        ));
    }

    public function find(Request $request)
    {
        $staffIdCard = $request->get("staff_id_card");
        $contractType = $request->get("contract_type");
        $staff = StaffPersonalInfo::with(['contract' => function ($q) {
            return $q->latest();
        }])->where('staff_id', $staffIdCard)->first();

        return response()->json([
            'status' => isset($staff),
            'staff' => $staff
        ]);
    }

    /**
     * @param mix $request
     */
    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $contract = Contract::find($request->get('id'));
            $currentCompanyCode = @json_decode($contract->contract_object)->company->code;
            $staffPersonalInfoId = @$contract->staff_personal_info_id;
            $deleted = $contract->delete();

            /** 
             * Find previous contract to update contract_last_date.
             * because an active base on staff personal info id.
             */
            $previousContract = Contract::where('staff_personal_info_id', $staffPersonalInfoId)
                                        ->orderBy('start_date', 'desc')->first();
            
            if ($previousContract) {

                $previousCompanyCode =  @json_decode($previousContract->contract_object)->company->code;

                /**
                 * - To re-active previouse contract can not apply cross company.
                 * - Only contract the same company.
                 */
                if ($previousCompanyCode == $currentCompanyCode) {
                    $data = [
                        "contract_object->contract_last_date" => date('Y-m-d H:i:s', strtotime('+50 years')),
                        "updated_by" => Auth::id(),
                        "updated_at" => date('Y-m-d H:i:s')
                    ];
                    $previousContract->updateRecord(@$previousContract->id, $data);
                }
            }

            DB::commit();
            return response()->json([
                'status' => isset($deleted),
                'data' => $deleted
            ]);

        } catch (\Throwable $th) {
            DB::rollback();
            throw $th;
        }
    }

    /**
     * Store contract by Type
     *
     * @param Request $request
     * @return \Exception|\Illuminate\Http\RedirectResponse
     */
    public function storeContractByType(Request $request)
    {
        $post = $request->all();
        $staffPersonalId = $post["staff_personal_info_id"];
        $contractType = $post['contract_type'];

        if ($contractType == CONTRACT_ACTIVE_TYPE['FDC']
            || $contractType == CONTRACT_ACTIVE_TYPE['UDC']
            || $contractType == CONTRACT_ACTIVE_TYPE['PROMOTE']
            || $contractType == CONTRACT_ACTIVE_TYPE['DEMOTE']
            || $contractType == CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION']) {
            $request->validate([
                'salary' => 'required',
                'currency' => 'required',
            ]);
        }

        DB::beginTransaction();
        try {

            $contract = new Contract();
            $staff_id_card = "";

            /**
             * Validate staff to sign contract base on type
             */
            $currentStaffContract = Contract::currentContract($staffPersonalId)->with('staffPersonalInfo')->first();

            // When user select contract type $contractType
            switch ($contractType) {
                // case CONTRACT_TYPE['PROBATION']:
                case CONTRACT_TYPE['FDC']:
                case CONTRACT_TYPE['UDC']:
                    //Last contract can be null or current contract must be probation
                    if (isset($currentStaffContract)) {
                        if (
                            // $currentStaffContract->contract_type == CONTRACT_TYPE['PROBATION'] ||
                            $currentStaffContract->contract_type == CONTRACT_TYPE['FDC'] ||
                            $currentStaffContract->contract_type == CONTRACT_TYPE['UDC']
                        ) {
                            // ករណីមានកុងជាប់កុងត្រានៅក្រុមហ៊ុនផ្សេង
                            if ($currentStaffContract->contract_object["company"]["code"] != @\auth()->user()->company_code)
                                return redirect()->back()->withErrors(['0' => 'បុគ្គលិកមានកុងត្រារួចហើយ!']);
                        }
                    }
                    break;

                case CONTRACT_TYPE['RESIGN']:
                case CONTRACT_TYPE['DEATH']:
                case CONTRACT_TYPE['LAY_OFF']:
                case CONTRACT_TYPE['TERMINATE']:
                case CONTRACT_TYPE['DEMOTE']:
                    if (!isset($currentStaffContract) || $currentStaffContract->contract_type == CONTRACT_TYPE['RESIGN']
                        || $currentStaffContract->contract_type == CONTRACT_TYPE['DEATH']
                        || $currentStaffContract->contract_type == CONTRACT_TYPE['TERMINATE']
                        || $currentStaffContract->contract_type == CONTRACT_TYPE['LAY_OFF']
                        || $currentStaffContract->contract_type == CONTRACT_TYPE['DEMOTE']
                    ) {
                        return redirect()->back()->withErrors(['0' => 'កុងត្រាដែលអាចធ្វើ RESIGN, DEATH, TERMINATE, LAY_OFF ត្រូវតែជាប្រភេទ Active Contract.']);
                    }
                    break;

                default:

            }

            $company_code = $post["company_code"];
            $company = Company::getCompanyByCode($company_code)->first();
            $branch_department_code = BranchesAndDepartments::GetByCode($post['branch_department_code'])->first();
            $position = Position::getByCompanyCode($company_code)->where('code', $post['position_code'])->first();

            $dateFrom = strtotime($post["contract_start_date"]);
            $dateTo = strtotime($post["contract_end_date"]);

            $contractObj = [
                "company" => new CompanyResource($company),
                "branch_department" => new DepartmentBranchResource($branch_department_code),
                "position" => new PositionResource($position),
            ];

            if (!empty($post['salary'])) {
                $contractObj['salary'] = convertSalaryFromStrToFloatValue(@$post['salary']);
            }

            if (!empty($post['currency'])) {
                $contractObj['currency'] = @$post['currency'];
            }

            if (!empty($post['probation_end_date'])) {
                $contractObj['probation_end_date'] = date('Y-m-d', strtotime($post["probation_end_date"]));
            }

            if (!empty($post['reason'])) {
                $contractObj['reason'] = @$post['reason'];
            }

            if (!empty($post['transfer_to_staff'])) {
                $transferWorkTo = Contract::staffReplace($post['transfer_work_to_staff_id'])->with('staffPersonalInfo')->first();
                if (!isset($transferWorkTo)) {
                    return redirect()->back()->withErrors(['0' => 'Sorry, Staff Transfer works could not available! ']);
                }
                $contractObj["transfer_work_to_staff"] = new TransferWorkToStaffResource($transferWorkTo);
            }

            if (!empty($post['get_work_form_staff'])) {
                $getWorkFrom = Contract::staffReplace($post['get_work_form_staff_id'])->with('staffPersonalInfo')->first();
                if (!isset($getWorkFrom)) {
                    return redirect()->back()->withErrors(['0' => 'Sorry, Staff gets working from could not available!']);
                }
                $contractObj["staff_get_work_from"] = new TransferWorkToStaffResource($getWorkFrom);
            }

            // Save file reference
            if (isset($post['file_reference'])) {
                $ext = $request->file('file_reference')->extension();
                $fileName = @$currentStaffContract->staffPersonalInfo->last_name_en . '_' . @$currentStaffContract->staffPersonalInfo->first_name_en . '_' . Uuid::uuid4() . '.' . $ext;
                $contractObj["file_reference"] = $request->file('file_reference')->storeAs('public/contract_form', $fileName);
            }

            if ($contractType == CONTRACT_TYPE['UDC']
                || $contractType == CONTRACT_TYPE['FDC']
                || $contractType == CONTRACT_TYPE['DEMOTE']
                || $contractType == CONTRACT_TYPE['PROMOTE']
                || $contractType == CONTRACT_TYPE['CHANGE_LOCATION']) {

                $contractObj["contract_last_date"] = date('Y-m-d H:i:s', strtotime('+50 years', $dateTo)); // save this field for check as current contract staff
                $staff_id_card = (!isset($currentStaffContract)) ? $contract->staffIdFormat($company_code) : $currentStaffContract->staff_id_card;

            } else if ($contractType == CONTRACT_TYPE['RESIGN']
                || $contractType == CONTRACT_TYPE['DEATH']
                || $contractType == CONTRACT_TYPE['TERMINATE']
                || $contractType == CONTRACT_TYPE['LAY_OFF']) {

                $contractObj["salary"] = @$currentStaffContract->contract_object['salary'];
                $contractObj["contract_last_date"] = date('Y-m-d H:i:s');
                $staff_id_card = $currentStaffContract->staff_id_card; // Keep old id card
            }
            //1 => company_paid, null or 0 => own paid
            $contractObj["pay_tax_status"] = isset($post["pay_tax_status"]) ? 1 : null;

            $data = [
                "staff_id_card" => $staff_id_card,
                "staff_personal_info_id" => $staffPersonalId,
                "company_profile" => ($post["company_code"] . $post["branch_department_code"] . $post["position_code"]),
                "contract_object" => $contractObj,//Contract object field depend on contract type
                "created_by" => Auth::id(),
                "created_at" => date('Y-m-d H:i:s'),
                "updated_by" => Auth::id(),
                "updated_at" => date('Y-m-d H:i:s'),
                "start_date" => date('Y-m-d H:i:s', $dateFrom),
                "end_date" => date('Y-m-d H:i:s', $dateTo),
                "contract_type" => $contractType
            ];

            $save = $contract->createRecord($data);

            if (isset($save)) {
                //Update contract last date for remove previous contract from current contract
                if (isset($currentStaffContract)) {
                    $updateRecord = $currentStaffContract->contract_object;
                    $updateRecord['contract_last_date'] = date('Y-m-d H:i:s', strtotime('-1 days'));//TODO: update previous contract here should be the same contract_last_date
                    $currentStaffContract->contract_object = $updateRecord;
                    $currentStaffContract->save();
                }

                DB::commit();
                return redirect()->route('contract.index')->with(['success' => 1]);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception;
        }
    }

    /**
     * Show form edit contract.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit($id)
    {
        $id = decrypt($id);
        $contract = Contract::with(['staffPersonalInfo'])->findOrFail($id);

        $user = Auth::user();
        if ($user->is_admin) {
            $companies = Company::all();
            $branchesDepartments = BranchesAndDepartments::all();
            $positions = Position::all();
        } else {
            $companies = Company::GetCompanyByCode(\auth()->user()->company_code)->get();
            $branchesDepartments = BranchesAndDepartments::getByCompanyCode(auth()->user()->company_code)->get();
            $positions = Position::getByCompanyCode(auth()->user()->company_code)->get();
        }

        $currency = CURRENCY;
        $contract_type = CONTRACT_TYPE;
        $reasons = DB::table('reason_company_note')->get();

        return view('contract.edit', compact(
            'contract',
            'currency',
            'companies',
            'branchesDepartments',
            'positions',
            'contract_type',
            'reasons'
        ));
    }

    /**
     * Update contract
     *
     * @param Request $request
     * @return \Exception|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $post = $request->all();
        $staffPersonalId = $post["staff_personal_info_id"];
        $contractType = $post['contract_type'];

        if ($contractType == CONTRACT_ACTIVE_TYPE['FDC']
            || $contractType == CONTRACT_ACTIVE_TYPE['UDC']
            || $contractType == CONTRACT_ACTIVE_TYPE['PROMOTE']
            || $contractType == CONTRACT_ACTIVE_TYPE['DEMOTE']
            || $contractType == CONTRACT_ACTIVE_TYPE['CHANGE_LOCATION']) {
            $request->validate([
                'salary' => 'required',
                'currency' => 'required',
            ]);
        }

        DB::beginTransaction();
        try {

            /**
             * Validate staff to sign contract base on type
             */
            $currentStaffContract = Contract::with('staffPersonalInfo')->findOrFail($request->input('contract_id'));

            $company_code = $post["company_code"];
            $company = Company::getCompanyByCode($company_code)->first();
            $branch_department_code = BranchesAndDepartments::GetByCode($post['branch_department_code'])->first();
            $position = Position::getByCompanyCode($company_code)->where('code', $post['position_code'])->first();

            $dateFrom = strtotime($post["contract_start_date"]);
            $dateTo = strtotime($post["contract_end_date"]);

            $contractObj = [
                "company" => new CompanyResource($company),
                "branch_department" => new DepartmentBranchResource($branch_department_code),
                "position" => new PositionResource($position),
            ];

            if (!empty($post['salary'])) {
                $contractObj['salary'] = convertSalaryFromStrToFloatValue(@$post['salary']);
                //Need to update new salary to update to day in salary
                $newSalary = NewSalary::where('contract_id', $request->input('contract_id'))->orderBy('effective_date', 'desc')->first();
                if (@$newSalary) {
                    $data = @$newSalary->object;
                    $data->new_salary = $contractObj['salary'];
                    $newSalary->object = $data;
                    $newSalary->save();
                }
            }

            if (!empty($post['currency'])) {
                $contractObj['currency'] = @$post['currency'];
            }

            if (!empty($post['probation_end_date'])) {
                $contractObj['probation_end_date'] = date('Y-m-d', strtotime($post["probation_end_date"]));
            }

            if (!empty($post['reason'])) {
                $contractObj['reason'] = @$post['reason'];
            }

            if (!empty($post['transfer_to_staff'])) {
                $transferWorkTo = Contract::staffReplace($post['transfer_work_to_staff_id'])->with('staffPersonalInfo')->first();
                if (!isset($transferWorkTo)) {
                    return redirect()->back()->withErrors(['0' => 'Sorry, Staff Transfer works could not available! ']);
                }
                $contractObj["transfer_work_to_staff"] = new TransferWorkToStaffResource($transferWorkTo);
            }

            if (!empty($post['get_work_form_staff'])) {
                $getWorkFrom = Contract::staffReplace($post['get_work_form_staff_id'])->with('staffPersonalInfo')->first();
                if (!isset($getWorkFrom)) {
                    return redirect()->back()->withErrors(['0' => 'Sorry, Staff gets working from could not available!']);
                }
                $contractObj["staff_get_work_from"] = new TransferWorkToStaffResource($getWorkFrom);
            }

            // Save file reference
            if (isset($post['file_reference'])) {
                $ext = $request->file('file_reference')->extension();
                $fileName = @$currentStaffContract->staffPersonalInfo->last_name_en . '_' . @$currentStaffContract->staffPersonalInfo->first_name_en . '_' . Uuid::uuid4() . '.' . $ext;
                $contractObj["file_reference"] = $request->file('file_reference')->storeAs('public/contract_form', $fileName);
            }

            $contractObj["pay_tax_status"] = isset($post["pay_tax_status"]) ? 1 : null;

            $data = [
                "staff_id_card" => $currentStaffContract->staff_id_card,
                "staff_personal_info_id" => $staffPersonalId,
                "company_profile" => ($post["company_code"] . $post["branch_department_code"] . $post["position_code"]),
                "contract_object->position" => new PositionResource($position),
                "contract_object->branch_department" => new DepartmentBranchResource($branch_department_code),
                "contract_object->company" => new CompanyResource($company),
                "contract_object->salary" => @$contractObj["salary"],
                "contract_object->currency" => @$contractObj["currency"],
                "contract_object->probation_end_date" => @$contractObj["probation_end_date"],
                "contract_object->reason" => @$contractObj["reason"],
                "contract_object->transfer_work_to_staff" => @$contractObj["transfer_work_to_staff"],
                "contract_object->staff_get_work_from" => @$contractObj["staff_get_work_from"],
                "contract_object->pay_tax_status" => @$contractObj["pay_tax_status"],
                "contract_object->contract_last_date" => @$currentStaffContract->contract_object['contract_last_date'],
                "contract_object->file_reference" => @$contractObj["file_reference"],
                "updated_by" => Auth::id(),
                "updated_at" => date('Y-m-d H:i:s'),
                "start_date" => date('Y-m-d H:i:s', $dateFrom),
                "end_date" => date('Y-m-d H:i:s', $dateTo),
                "contract_type" => $contractType
            ];

            $contract = new Contract();
            $contract_id = $request->input('contract_id');
            $update = $contract->updateRecord($contract_id, $data);

            if (isset($update)) {
                DB::commit();
                return redirect()->route('contract.index')->with(['success' => 1]);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception;
        }
    }

    /**
     * Get last contract of staff.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentContract($id)
    {
        $contract = Contract::CurrentContract($id)->first();
        if ($contract == NULL) {
            return response()->json(['data' => 'រកបុគ្គលិកមិនឃើញទេ!', 'status' => false]);
        }
        return \response()->json(['data' => $contract, 'status' => true]);
    }
}
