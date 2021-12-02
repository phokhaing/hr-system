<?php


namespace Modules\Payroll\Helpers;

use Modules\Payroll\Entities\SysTaxParams;

class CalculateSalaryTax
{

    const company_paid_tax = 1;

    private $contract;
    private $currency;
    private $exchangeRate;

    public function __construct($contract, $currency)
    {
        $this->contract = $contract;
        $this->currency = $currency;
        $this->exchangeRate = getExchangeRate();
    }

    /**
     * Salary Tax Formula => (salary_before_tax - spouse_children_amount) * tax_rate - tax_deduction
     * Tax Charge follow government law, go throw this link https://trello.com/c/dljZ1YHU/64-payroll-document
     * @param $salaryBeforeTax
     * @param int $spouseAmount are always khmer
     */
    public function calculateTax($salaryBeforeTax, $spouseAmount = 0): array
    {
        //Tax charge only in Riel(KHR), so need to check and convert salary from USD to KHR
        $salaryBeforeTax = $this->checkToCovertUsdSalaryToKhr($salaryBeforeTax);
        $salaryAfterSpouse = $salaryBeforeTax - $spouseAmount;
        $taxParams = $this->taxRate($salaryAfterSpouse);
        $taxRate = @$taxParams->tax_rate;
        $taxDeduction = @$taxParams->tax_deduction;
        $taxOnSalary = ($salaryAfterSpouse * @$taxRate) - @$taxDeduction;
        return [
            'tax_on_salary' => $taxOnSalary,
            'tax_rate' => $taxRate
        ];
    }

    /**
     * Tax rate follow government law, go throw this link https://trello.com/c/dljZ1YHU/64-payroll-document
     * @param $grossSalary
     * @return mixed
     */
    public function taxRate($grossSalary)
    {
        $tax = SysTaxParams::where('tax_object->tax_range_from', '<=', $grossSalary)
            ->where('tax_object->tax_range_to', '>=', $grossSalary)
            ->first();
        return @$tax->tax_object;
    }

    /**
     *  Convert base USD salary to KHR to find tax charge
     * @param $salary
     * @return float|int|mixed
     */
    public function checkToCovertUsdSalaryToKhr($salary): float
    {
        //Need to convert USD to KHR due to every salary post in KHR currency (currently)
        if ($this->currency == STORE_CURRENCY_USD) {
            $salary = $salary * $this->exchangeRate;
        }
        return $salary;
    }

    /**
     * Convert salary after tax from KHR to USD back
     * @param $salary
     * @return float
     */
    public function checkToCovertKhrSalaryToUsd($salary): float
    {
        //Need to convert USD to KHR due to every salary post in KHR currency (currently)
        if ($this->currency == STORE_CURRENCY_USD) {
            $salary = $salary / $this->exchangeRate;
        }
        return $salary;
    }

    /**
     * pay_tax_status [null or 0 => staff_paid, 1 => company_paid]
     * @return bool
     */
    public function checkIsCompanyPaidTax(): bool
    {
        $isCompanyPaid = @$this->contract['contract_object']['pay_tax_status'];
        return @$isCompanyPaid == self::company_paid_tax;
    }
}