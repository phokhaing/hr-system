<table style="width: 100%">
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <td colspan="13">
            <b>{{ @$company->name_en }}</b>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>Branch:</b>
        </td>
        <td colspan="11">
            <b>{{ @$company->short_name }}</b>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <b>For:</b>
        </td>
        <td colspan="11">
            <b>{{ @$transaction_date ? date('d-M-Y', strtotime(@$transaction_date)) : '' }}</b>
        </td>
    </tr>
</table>

<table>
    <thead>
    <tr>
        <th style="background-color: #d5d5d5"><b>No</b></th>
        <th style="background-color: #d5d5d5"><b>SID</b></th>
        <th style="background-color: #d5d5d5"><b>Name In English</b></th>
        <th style="background-color: #d5d5d5"><b>Sex</b></th>
        <th style="background-color: #d5d5d5"><b>Position</b></th>
        <th style="background-color: #d5d5d5"><b>Bank Account Number</b></th>
        <th style="background-color: #d5d5d5"><b>Location</b></th>
        <th style="background-color: #d5d5d5"><b>D.O.E</b></th>
        <th style="background-color: #d5d5d5"><b>Effective date</b></th>
        <th style="background-color: #d5d5d5"><b>Gross base salary</b></th>
        <th style="background-color: #d5d5d5"><b>Gross basic salary</b></th>
        <th style="background-color: #d5d5d5"><b>Retroactive payment</b></th>
        <th style="background-color: #d5d5d5"><b>Degree allowance</b></th>
        <th style="background-color: #d5d5d5"><b>Monthly Incentive And Top Up Salary</b></th>
        <th style="background-color: #d5d5d5"><b>Location allowance</b></th>
        <th style="background-color: #d5d5d5"><b>Maternity Leave/ AL</b></th>
        <th style="background-color: #d5d5d5"><b>Leave without pay Late</b></th>
        <th style="background-color: #d5d5d5"><b>Salary Before Tax</b></th>
        <th style="background-color: #d5d5d5"><b>Spouse</b></th>
        <th style="background-color: #d5d5d5"><b>Children</b></th>
        <th style="background-color: #d5d5d5"><b>Dependents</b></th>
        <th style="background-color: #d5d5d5"><b>Salary Tax Calculation Base</b></th>
        <th style="background-color: #d5d5d5"><b>Tax on salary</b></th>
        <th style="background-color: #d5d5d5"><b>Fringe Allowance</b></th>
        <th style="background-color: #d5d5d5"><b>Tax on Fringe</b></th>
        <th style="background-color: #d5d5d5"><b>Total Tax Payable</b></th>
        <th style="background-color: #d5d5d5"><b>Pay after Taxes</b></th>
        <th style="background-color: #d5d5d5"><b>អតីតភាពការងារ</b></th>
        <th style="background-color: #d5d5d5"><b>Staff loan deduction And Other</b></th>
        <th style="background-color: #d5d5d5"><b>Pension Fund</b></th>
        <th style="background-color: #d5d5d5"><b>MMI deduction</b></th>
        <th style="background-color: #d5d5d5"><b>Other Dedution</b></th>
        <th style="background-color: #d5d5d5"><b>Half Month</b></th>
        <th style="background-color: #d5d5d5"><b>Total deduction</b></th>
        <th style="background-color: #d5d5d5"><b>Net Pay</b></th>
    </tr>
    </thead>
    @if(isset($payroll_object))
        <tbody>
        @php
            $totalGBaseSalary = 0;
            $totalGBasicSalary = 0;
            $totalRetroSalary = 0;
            $totalDegree = 0;
            $totalIncentive = 0;
            $totalLocation = 0;
            $totalMaternityLeave = 0;
            $totalUnpaidLeave = 0;
            $totalSalaryBeforeTax = 0;
            $totalSpouse = 0;
            $totalChildren = 0;
            $totalDependents = 0;
            $totalSalaryTaxCalculationBase = 0;
            $totalTaxOnSalary = 0;
            $totalFringe = 0;
            $totalFringeTax = 0;
            $totalTaxPayable = 0;
            $totalSalaryAfterTax = 0;
            $totalSeniority = 0;
            $totalStaffLoanPaid = 0;
            $totalPensionFund = 0;
            $totalInsurancePay = 0;
            $totalSalaryDeduction = 0;
            $totalHalfSalary = 0;
            $totalTotalDeduction = 0;
            $totalNetSalary = 0;
        @endphp
        @foreach($payroll_object as $key => $payroll)
            <?php
            $salaryForCalculationTaxBase = @$payroll->salary_before_tax - @$payroll->spouse->amount;
            $spouseCount = @$payroll->spouse ? 1 : 0;
            $contractSalary = convertSalaryFromStrToFloatValue(@$payroll->contract_salary);

            $totalGBaseSalary += $contractSalary;
            $totalGBasicSalary += @$payroll->gross_basic_salary;
            $totalRetroSalary += @$payroll->retroactive_salary;
            $totalDegree += @$payroll->degree_allowance;
            $totalIncentive += @$payroll->incentive;
            $totalLocation += @$payroll->location_allowance;
            $totalMaternityLeave += @$payroll->maternity_leave;
            $totalUnpaidLeave += @$payroll->unpaid_leave;
            $totalSalaryBeforeTax += @$payroll->salary_before_tax;
            $totalSpouse += @$spouseCount;
            $totalChildren += @$payroll->spouse->spouse->children_no;
            $totalDependents += @$payroll->spouse->amount;
            $totalSalaryTaxCalculationBase += @$salaryForCalculationTaxBase;
            $totalTaxOnSalary += @$payroll->tax_on_salary;
            $totalFringe += @$payroll->fringe_allowance;
            $totalFringeTax += @$payroll->tax_on_fringe_allowance;
            $totalTaxPayable += @$payroll->total_tax_payable;
            $totalSalaryAfterTax += @$payroll->salary_after_tax;
            $totalSeniority += @$payroll->seniority_pay;
            $totalStaffLoanPaid += @$payroll->staff_loan_paid;
            $totalPensionFund += @$payroll->pension_fund;
            $totalInsurancePay += @$payroll->insurance_pay;
            $totalSalaryDeduction += @$payroll->salary_deduction;
            $totalHalfSalary += @$payroll->half_salary;
            $totalTotalDeduction += @$payroll->total_deduction;
            $totalNetSalary += @$payroll->net_salary;
            ?>
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ @$payroll->staff_id }}</td>
                <td>{{ @$payroll->staff_full_name }}</td>
                <td>{{ @$payroll->sex }}</td>
                <td>{{ @$payroll->staff_position }}</td>
                <td>{{ @$payroll->bank_acc_no }}</td>
                <td>{{ @$payroll->staff_location }}</td>
                <td>{{ date_readable($payroll->staff_effective_date) }}</td>
                <td>{{ date_readable($payroll->staff_effective_date) }}</td>
                <td>{{ @$contractSalary }}</td>
                <td>{{ @$payroll->gross_basic_salary }}</td>
                <td>{{ @$payroll->retroactive_salary }}</td>
                <td>{{ @$payroll->degree_allowance }}</td>
                <td>{{ @$payroll->incentive }}</td>
                <td>{{ @$payroll->location_allowance }}</td>
                <td>{{ @$payroll->maternity_leave }}</td>
                <td>{{ @$payroll->unpaid_leave }}</td>
                <td>{{ @$payroll->salary_before_tax }}</td>
                <td>{{ @$spouseCount }}</td>
                <td>{{ @$payroll->spouse->spouse->children_no }}</td>
                <td>{{ @$payroll->spouse->amount }}</td>
                <td>{{ @$salaryForCalculationTaxBase }}</td>
                <td>{{ @$payroll->tax_on_salary }}</td>
                <td>{{ @$payroll->fringe_allowance }}</td>
                <td>{{ @$payroll->tax_on_fringe_allowance }}</td>
                <td>{{ @$payroll->total_tax_payable }}</td>
                <td>{{ @$payroll->salary_after_tax  }}</td>
                <td>{{ @$payroll->seniority_pay  }}</td>
                <td>{{ @$payroll->staff_loan_paid  }}</td>
                <td>{{ @$payroll->pension_fund  }}</td>
                <td>{{ @$payroll->insurance_pay  }}</td>
                <td>{{ @$payroll->salary_deduction  }}</td>
                <td>{{ @$payroll->half_salary  }}</td>
                <td>{{ @$payroll->total_deduction  }}</td>
                <td>{{ @$payroll->net_salary  }}</td>
            </tr>
        @endforeach
        <tr></tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <b>Grand Total</b>
            </td>
            <td><b>{{ $totalGBaseSalary }}</b></td>
            <td><b>{{ $totalGBasicSalary }}</b></td>
            <td><b>{{ $totalRetroSalary }}</b></td>
            <td><b>{{ $totalDegree }}</b></td>
            <td><b>{{ $totalIncentive }}</b></td>
            <td><b>{{ $totalLocation }}</b></td>
            <td><b>{{ $totalMaternityLeave }}</b></td>
            <td><b>{{ $totalUnpaidLeave }}</b></td>
            <td><b>{{ $totalSalaryBeforeTax }}</b></td>
            <td><b>{{ $totalSpouse }}</b></td>
            <td><b>{{ $totalChildren }}</b></td>
            <td><b>{{ $totalDependents }}</b></td>
            <td><b>{{ $totalSalaryTaxCalculationBase }}</b></td>
            <td><b>{{ $totalTaxOnSalary }}</b></td>
            <td><b>{{ $totalFringe }}</b></td>
            <td><b>{{ $totalFringeTax }}</b></td>
            <td><b>{{ $totalTaxPayable }}</b></td>
            <td><b>{{ $totalSalaryAfterTax }}</b></td>
            <td><b>{{ $totalSeniority }}</b></td>
            <td><b>{{ $totalStaffLoanPaid }}</b></td>
            <td><b>{{ $totalPensionFund }}</b></td>
            <td><b>{{ $totalInsurancePay }}</b></td>
            <td><b>{{ $totalSalaryDeduction }}</b></td>
            <td><b>{{ $totalHalfSalary }}</b></td>
            <td><b>{{ $totalTotalDeduction }}</b></td>
            <td><b>{{ $totalNetSalary }}</b></td>

        </tr>
        </tbody>
    @endif
</table>