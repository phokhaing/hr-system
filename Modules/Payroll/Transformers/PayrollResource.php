<?php

namespace Modules\Payroll\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class PayrollResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        if (!is_null(@$this->transaction_object->gross_base_salary)) {
            $baseSalary = @$this->transaction_object->gross_base_salary;
        } else {
            $baseSalary = @$this->contract->contract_object["salary"];
        }
        return [
            'transaction_id' => @$this->id,
            'staff_id' => $this->staff_personal_info->staff_id,
            'name_in_english' => $this->staff_personal_info->full_name_english,
            'position' => @$this->contract->contract_object['position']['name_kh'],
            'location' =>  @$this->contract->contract_object['branch_department']['name_kh'],
            'base_salary' => @$baseSalary,
            'half_month' => number_format($this->transaction_object->amount, 2),
            'currency' => $this->transaction_object->ccy ?: 'N/A',
            'transaction_date' => date_readable($this->created_at),
            'posted_by' => @$this->user->full_name,
            'is_block' => @$this->transaction_object->is_block ?? false,
        ];
    }
}
