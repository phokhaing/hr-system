<?php

namespace Modules\Payroll\Transformers;

use Illuminate\Http\Resources\Json\Resource;

class TempTransactionResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $nameInEnglish = @$this->last_name_en . ' ' . @$this->first_name_en;
        $fringeTax = @$this->tax_on_fringe_allowance;
        return [
            'transaction_id' => @$this->id,
            'staff_id' => @$this->staff_id,
            'name_in_english' => $nameInEnglish,
            'position' => @$this->position,
            'location' => @$this->branch_department,
            'D.O.E' => date('d-M-Y', strtotime(@$this->start_date)),
            'effective_date' => date('d-M-Y', strtotime(@$this->start_date)),
            'gross_base_salary' => @$this->base_salary,
            'total_allowance' => @number_format(@$this->total_allowance, 2),
            'salary_before_tax' => @number_format(@$this->salary_before_tax, 2),
            'tax_on_salary' => @number_format(@$this->tax_on_salary, 2),
            'total_tax_payable' => @number_format(@$this->total_tax_payable, 2),
            'salary_after_tax' => @number_format(@$this->salary_after_tax, 2),
            'total_deduction' => @number_format(@$this->total_deduction, 2),
            'staff_loan_paid' => @number_format(@$this->staff_loan_paid, 2),
            'insurance_pay' => @number_format(@$this->insurance_pay, 2),
            'half_month' => @number_format(@$this->half_salary, 2),
            'pension_fund' => @number_format(@$this->pension_fund, 2),
            'fringe_allowance' => @number_format(@$this->fringe_allowance, 2),
            'tax_on_fringe_allowance' => @number_format(@$fringeTax, 2),
            'net_salary' => @number_format(@$this->net_salary, 2),
            'is_block' => @$this->is_block ?? false,
            'nssf' => @number_format(@$this->nssf, 2),
            'seniority_pay' => @number_format(@$this->seniority_pay, 2),
            'retroactive_salary' => @number_format(@$this->retroactive_salary, 2),
            'spouse' => @number_format(@$this->spouse, 2)
        ];
    }
}
