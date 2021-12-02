<?php

namespace Modules\PensionFund\Imports;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class PensionFundVerify implements ToCollection, WithCalculatedFormulas
{
    public $data = [];

    public function collection(Collection $rows)
    {

        DB::beginTransaction();
        try {

            unset($rows[0]);

            foreach ($rows as $row) {
                $row[0] = str_pad($row[0], 4, '0', STR_PAD_LEFT);
                // $row[5] = $this->transformDate($row[5]);
                // $row[6] = $this->transformDate($row[6]);
                $row[5] = '2021-01-01';
                $row[6] = '2021-01-01';
                $row[7] = @$row[7];
                $row[8] = @$row[8];
                $row[9] = @$row[9];
                $row[10] = @$row[10];
                $row[11] = @$row[11];
                $row[12] = @$row[12];
                $row[13] = @$row[13];
                // $row[14] = $this->transformDate($row[14]);
                $row[14] = '2021-01-01';
            }

            $this->data = $rows;

        } catch (\Exception $ex) {
            DB::rollBack();
            dd($ex);
            throw $ex;
        }

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
     * @return int
     */
    public function batchSize(): int
    {
        return 500;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 500;
    }

}
