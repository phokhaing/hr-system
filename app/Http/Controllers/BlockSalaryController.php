<?php

namespace App\Http\Controllers;

use App\Company;
use App\Contract;
use App\Exports\ReportLastDayBlockSalaryExport;
use App\FinalPay;
use App\Http\Resources\ReportLastDayBlockSalaryResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;

class BlockSalaryController extends BaseResponseController
{
    public function index(Request $request)
    {
        $keyword = @$request->keyword;
        $contracts = null;

        if (@$keyword) {
            $contracts = Contract::with(['staffPersonalInfo'])
                ->getAllStaffActive()
                ->whereHas('staffPersonalInfo', function ($query) use ($keyword) {
                    $query->whereRaw("concat(last_name_en, ' ', first_name_en) like '%$keyword%'")
                        ->orWhereRaw("concat(last_name_kh, ' ', first_name_kh) like '%$keyword%'")
                        ->orWhere("staff_id", $keyword);
                })
                ->get();
        }

        return view('block_salary.index', compact('contracts'));
    }

    public function blockOrUnBlock(Request $request)
    {
        //Validate contract is already final pay cannot process block or unblock again
        $isHadFinalPay = FinalPay::where('contract_id', @$request->contract_id)->first();
        if (@$isHadFinalPay) {
            return redirect()->back()->withErrors(['0' => 'Sorry, this staff had already FINAL PAY, so you could not update anything!']);
        }

        $contract = Contract::find($request->contract_id);
        $data = $contract->contract_object;
        if ($request->block_or_unblock) {
            $fromDate = $request->block_from_date;
            $untilDate = $request->block_until_date;

            //Save file reference
            if ($request->file('file_reference')) {
                $ext = $request->file('file_reference')->extension();
                $fileName = @$contract->staffPersonalInfo->last_name_en . '_' . @$contract->staffPersonalInfo->first_name_en . '_' . Uuid::uuid4() . '.' . $ext;
                $fileReference = $request->file('file_reference')->storeAs('public/contract_form', $fileName);
            } else {
                //If not update file, will take old one
                $fileReference = @$contract->contract_object['block_salary']['file_reference'];
            }


            $data['block_salary'] = [
                'is_block' => 1,
                'from_date' => date('Y-m-d', strtotime($fromDate)),
                'until_date' => date('Y-m-d', strtotime($untilDate)),
                'notice' => @trim(@$request->block_salary_notice),
                'date_range' => $this->calculateBlockSalaryDateRange($fromDate, $untilDate),
                'transfer_to_staff' => @$request->transfer_to_staff,
                'transfer_to_staff_name' => @$request->transfer_to_staff_name,
                'contract_type' => @$request->contract_type,
                'file_reference' => @$fileReference,
            ];
        } else {
            //Save previous history of block information
            $previousBlockSalaryObj = @$contract->contract_object['block_salary'];
            $previousBlockSalaryObj['is_block'] = 0;
            $data['block_salary'] = $previousBlockSalaryObj;
        }
        $contract->contract_object = $data;
        $save = $contract->save();
        if ($save) {
            return redirect()->route('contract.index')->with(['success' => 1]);
        } else {
            return redirect()->back()->withErrors(['0' => 'Something was wrong!']);
        }
    }

    public function calculateBlockSalaryDateRange($originalStart, $originalEnd)
    {
        $firstDate = date('M-Y', strtotime($originalStart));
        $secondDate = date('M-Y', strtotime($originalEnd));

        $fromDate = Carbon::parse($firstDate);
        $untilDate = Carbon::parse($secondDate);
        $months = [];
        do {
            $start = $fromDate->format('Y-m-d');
            $endDate = Carbon::parse($start);

            if ($endDate == $untilDate) {
                $end = Carbon::parse($originalEnd)->format('Y-m-d');
            } else {
                $end = $endDate->endOfMonth()->format('Y-m-d');
            }
            $totalDays = $fromDate->diffInDays(Carbon::parse($end)) + 1;

            $months[] = [
                'first_date_of_month' => $start,
                'last_date_of_month' => $end,
                'days_in_month' => $endDate->daysInMonth,
                'total_days' => $totalDays
            ];
        } while ($fromDate->addMonth() <= $untilDate);
        return $months;
    }

    function blockLastDay($id)
    {
        $contract = Contract::with(['staffPersonalInfo'])->findOrFail($id);
        return view('block_salary.block_last_day', compact(
            'contract'
        ));
    }

    function report(Request $request)
    {
        try {
            $isDownload = $request->input('is_download');
            $companyCode = $request->input('company_code');
            $branchCode = $request->input('branch_department_code');
            $positionCode = $request->input('position_code');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $contracts = Contract::with(['staffPersonalInfo' => function ($q) {
                $q->select('id', 'first_name_en', 'last_name_en', 'first_name_kh', 'last_name_kh', 'staff_id', 'gender');
            }])
                ->getAllStaffActive()
                ->where('contract_object->block_salary->is_block', 1)//Only staff who has been done last day/block salary available to checking final pay
                ->doesntHave('finalPay');//Staff already checking final pay who did not posted yet, could not available to checking again, but can edit

            if (@$startDate && @$endDate) {
                $contracts->where('contract_object->block_salary->from_date', '>=', date('Y-m-d', strtotime($startDate)))
                    ->where('contract_object->block_salary->from_date', '<=', date('Y-m-d', strtotime($endDate)));
            }

            $user = Auth::user();
            $is_admin = @$user->is_admin;
            if ($is_admin) {
                if ($companyCode) {
                    $contracts->where('contract_object->company->code', (int)$companyCode);
                }
            } else {
                $companyCode = @$user->company_code;
                $contracts->where('contract_object->company->code', (int)$companyCode);
            }

            if (@$branchCode) {
                $contracts->where('contract_object->branch_department->code', (int)$branchCode);
            }

            if (@$positionCode) {
                $contracts->where('contract_object->position->code', (int)$positionCode);
            }

            $contracts = $contracts->get();

            if ($isDownload) {
                $company = Company::where('company_code', (int)@$companyCode)->first();
                return Excel::download(new ReportLastDayBlockSalaryExport(
                    $contracts,
                    $startDate,
                    $endDate,
                    @$company
                ), 'report_last_day_block_salary.xlsx');
            } else {
                return ReportLastDayBlockSalaryResource::collection($contracts);
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
}
