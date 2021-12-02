<?php


namespace Modules\Payroll\Imports;


use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\Payroll\Entities\TempTransactionUpload;


class DeductionImport implements ToCollection
{
    /**
     * @param Collection $collection
     * @return bool
     */
    public function collection(Collection $collection)
    {
        /**
         * 0 => "ID"
         * 1 => "Staff Id"
         * 2 => "Name EN"
         * 3 => "Position"
         * 4 => "Location"
         * 5 => "Transaction Code"
         * 6 => "Before or After Tax"
         * 7 => "Amount"
         * 8 => "Currency"
         * 9 => "Remark"
         */

        DB::beginTransaction();
        try {
            $collection = $collection->forget(0)->filter(function ($item) {
                return !is_null(@$item[1]) && @$item[1] > 0;
            });
            $this->checkContractHasUploaded($collection);

            foreach ($collection as $key => $row) {
                // Skip uncompleted data in each row.
                if (
                    is_null($row[1]) or
                    is_null($row[5]) or
                    is_null($row[6]) or
                    is_null($row[7]) or
                    is_null($row[8])
                ) {
                    continue;
                }

                $staff_group_id = (int)$row[1];
                $staffPersonalInfo = StaffPersonalInfo::with('currentContract')
                    ->where('staff_id', $staff_group_id)->first();

                $contract = @$staffPersonalInfo->currentContract;

                $transaction_arr = explode("-", @$row[5]);
                $transaction_id = @$transaction_arr[0];

                $deduction_status_arr = explode("-", @$row[6]);
                $deduction_status = (int)trim(@$deduction_status_arr[0]);

                $amount = trim(@$row[7]);
                $currency = trim(@$row[8]);
                $remark = trim(@$row[9]);

                if (@$currency == \STORE_CURRENCY_KHR && @$transaction_id != TRANSACTION_CODE['NSSF']) {
                    $amount = round(@$amount, -2);
                }

                $data = [
                    'contract_id' => @$contract->id,
                    'staff_personal_info_id' => @$contract->staff_personal_info_id,
                    'transaction_code_id' => @$transaction_id,
                    'transaction_object' => [
                        'amount' => @$amount,
                        'ccy' => @$currency,
                        'before_or_after_tax' => @$deduction_status,
                        'remark' => @$remark
                    ]
                ];
                $tempTran = new TempTransactionUpload();
                $saved = $tempTran->createRecord($data);

                // if ($staff_group_id == 7) {
                //     dd($staffPersonalInfo, $saved, $data, $contract);
                // }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }

    /**
     * Check to delete all previous uploads by staff,contract for re-calculate checking staff again
     * Upload flow can upload new staff or existing staff for checking payroll, 
     * this will never clear previous upload for case new upload
     */
    private function checkContractHasUploaded($uploadedItems)
    {
        //Where unique by staff_id
        $uniqueCollection = @$uploadedItems->unique(1);
        foreach ($uniqueCollection as $key => $item) {
            $staff_group_id = @$item[1];
            $staffPersonalInfo = StaffPersonalInfo::select('id')
                ->with(['currentContract' => function ($q) {
                    return $q->select(['id', 'staff_personal_info_id']);
                }])
                ->where('staff_id', $staff_group_id)
                ->whereHas('tempTransactionUploads')
                ->first();
            if (@$staffPersonalInfo) {
                dd($staffPersonalInfo);
                $contractId = @$staffPersonalInfo->currentContract->id;
                TempTransactionUpload::where('contract_id', $contractId)->delete();
            }
        }
        @$uploadedItems->unique(1)->map(function ($item) {
            $staff_group_id = @$item[1];
            $staffPersonalInfo = StaffPersonalInfo::select('id')
                ->with(['currentContract' => function ($q) {
                    return $q->select(['id', 'staff_personal_info_id']);
                }])
                ->where('staff_id', $staff_group_id)->first();
            if (@$staffPersonalInfo) {

                $contractId = @$staffPersonalInfo->currentContract->id;
                TempTransactionUpload::where('contract_id', $contractId)->delete();
            }
        });
    }
}
