<?php


namespace Modules\Payroll\Imports;


use App\StaffInfoModel\StaffPersonalInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Modules\Payroll\Entities\TempTransactionUpload;


class PreviewDeductionFIle implements ToCollection
{
    public $items;
    /**
     * @param Collection $collection
     * @return bool
     */
    // public function collection(Collection $collection)
    // {
    //     /**
    //      * 0 => "ID"
    //      * 1 => "Staff Id"
    //      * 2 => "Name EN"
    //      * 3 => "Position"
    //      * 4 => "Location"
    //      * 5 => "Transaction Code"
    //      * 6 => "Before or After Tax"
    //      * 7 => "Amount"
    //      * 8 => "Currency"
    //      * 9 => "Remark"
    //      */

    //     DB::beginTransaction();
    //     try {
    //         $collection->forget(0); //Remove header in each columns.
    //         $data = [];
    //         foreach ($collection as $key => $row) {
    //             // Skip uncompleted data in each row.
    //             if (
    //                 is_null(@$row[1]) or
    //                 is_null(@$row[5]) or
    //                 is_null(@$row[6]) or
    //                 is_null(@$row[7]) or
    //                 is_null(@$row[8])
    //             ) {
    //                 continue;
    //             }
    //             $data[] = $row;
    //         }
    //         $this->items = $data;
    //     } catch (\Exception $e) {
    //         return $e;
    //     }

    // }

    public function collection(Collection $rows)
    {
        $rows->forget(0);
        $this->items = $rows->transform(function ($row) {
            // dd($row, @$row[5]);
            return [
                'no' => @$row[0],
                'staff_id' => @$row[1],
                'name' => @$row[2],
                'positon' => @$row[3],
                'location' => @$row[4],
                'transaction_code' => @$row[5],
                'before_or_after_tax' => @$row[6],
                'amount' => @$row[7],
                'currency' => @$row[8],
                'remark' => @$row[9],
            ];
            return $row;
        });
        dd($this->items );
    }
}
