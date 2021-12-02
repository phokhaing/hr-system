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
            <b>Office:</b>
        </td>
        <td colspan="11">
            <b>Consolidate</b>
        </td>
    </tr>
    <tr>
        <td colspan="13">
            <b>Summary Payroll By Branch</b>
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
        <th style="background-color: #d5d5d5">
            <b>No</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Branch Code</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Short Name</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Br. Name in English</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Status</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>M</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>F</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b># of staff</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Gross base salary</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Gross basic salary</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Retroactive payment</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Degree allowance</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Monthly Incentive And Top Up Salary</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Location allowance</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Maternity Leave/ AL</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Leave without pay Late</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Salary Before Tax</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Spouse</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Children</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Dependents</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Salary Tax Calculation Base</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Tax on salary</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Fringe Allowance</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Tax on Fringe</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Total Tax Payable</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Pay after Taxes</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Seniority</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Staff loan deduction And Other</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Pension Fund</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>MMI deduction</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Other Deduction</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Half Month</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Total deduction</b>
        </th>
        <th style="background-color: #d5d5d5">
            <b>Net Pay</b>
        </th>
    </tr>
    </thead>
    @if(isset($payroll_object))
        <tbody>
        @php
            $totalM = 0;
            $totalF = 0;
            $totalStaff = 0;
            $totalGbaseSalary = 0;
            $totalGbasicSalary = 0;
            $totalRetro = 0;
            $totalDegree = 0;
            $totalMonthlyAndTopUpSalary = 0;
            $totalLocation = 0;
            $totalMaternity = 0;
            $totalLeaveWithoutPay = 0;
            $totalSalaryBeforeTax = 0;
            $totalSpouse = 0;
            $totalChildren = 0;
            $totalDependents = 0;
            $totalSalaryTaxCalculationBase = 0;
            $totalTaxOnSalary = 0;
            $totalFringe = 0;
            $totalTaxonFringe = 0;
            $totalTaxPayable = 0;
            $totalPayAfterTax = 0;
            $totalSeniority = 0;
            $totalStaffLoanDeduction = 0;
            $totalPensionFund = 0;
            $totalMMIDeduction = 0;
            $totalOtherDeduction = 0;
            $totalHalfMonth = 0;
            $totalDeduction = 0;
            $totalNetPay = 0;
        @endphp
        @foreach($payroll_object as $key => $payroll)
            <?php
            $staffs = @$payroll->male + @$payroll->female;
            $salaryForCalculationTax = @$payroll->salary_before_tax - @$payroll->spouse_amount;
            $contractSalary = convertSalaryFromStrToFloatValue(@$payroll->contract_salary);

            $totalM += @$payroll->male;
            $totalF += @$payroll->female;
            $totalStaff += @$staffs;
            $totalGbaseSalary += @$contractSalary;
            $totalGbasicSalary += @$payroll->gross_basic_salary;
            $totalRetro += @$payroll->retroactive_salary;
            $totalDegree += @$payroll->degree_allowance;
            $totalMonthlyAndTopUpSalary += @$payroll->incentive;
            $totalLocation += @$payroll->location_allowance;
            $totalMaternity += @$payroll->maternity_leave;
            $totalLeaveWithoutPay += @$payroll->unpaid_leave;
            $totalSalaryBeforeTax += @$payroll->salary_before_tax;
            $totalSpouse += @$payroll->spouse;
            $totalChildren += @$payroll->children_no;
            $totalDependents += @$payroll->spouse_amount;
            $totalSalaryTaxCalculationBase += @$salaryForCalculationTax;
            $totalTaxOnSalary += @$payroll->tax_on_salary;
            $totalFringe += @$payroll->fringe_allowance;
            $totalTaxonFringe += @$payroll->tax_on_fringe_allowance;
            $totalTaxPayable += @$payroll->total_tax_payable;
            $totalPayAfterTax += @$payroll->salary_after_tax;
            $totalSeniority += @$payroll->seniority_pay;
            $totalStaffLoanDeduction += @$payroll->staff_loan_paid;
            $totalPensionFund += @$payroll->pension_fund;
            $totalMMIDeduction += @$payroll->insurance_pay;
            $totalOtherDeduction += @$payroll->salary_deduction;
            $totalHalfMonth += @$payroll->half_salary;
            $totalDeduction += @$payroll->total_deduction;
            $totalNetPay += @$payroll->net_salary;
            ?>
            <tr>
                <td>{{ ++$key }}</td>
                <td>{{ @$payroll->branch_code  }}</td>
                <td>{{ @$payroll->branch_short_name  }}</td>
                <td>{{ @$payroll->branch_name  }}</td>
                <td>{{ @$payroll->company_short_name  }}</td>
                <td>{{ @$payroll->male  }}</td>
                <td>{{ @$payroll->female  }}</td>
                <td>{{ @$staffs  }}</td>
                <td>{{ @$contractSalary  }}</td>
                <td>{{ @$payroll->gross_basic_salary  }}</td>
                <td>{{ @$payroll->retroactive_salary  }}</td>
                <td>{{ @$payroll->degree_allowance  }}</td>
                <td>{{ @$payroll->incentive  }}</td>
                <td>{{ @$payroll->location_allowance  }}</td>
                <td>{{ @$payroll->maternity_leave  }}</td>
                <td>{{ @$payroll->unpaid_leave  }}</td>
                <td>{{ @$payroll->salary_before_tax  }}</td>
                <td>{{ @$payroll->spouse  }}</td>
                <td>{{ @$payroll->children_no  }}</td>
                <td>{{ @$payroll->spouse_amount  }}</td>
                <td>{{ @$salaryForCalculationTax  }}</td>
                <td>{{ @$payroll->tax_on_salary  }}</td>
                <td>{{ @$payroll->fringe_allowance  }}</td>
                <td>{{ @$payroll->tax_on_fringe_allowance  }}</td>
                <td>{{ @$payroll->total_tax_payable  }}</td>
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
            <td>
                <b>Grand Total</b>
            </td>
            <td><b>{{@$totalM}}</b></td>
            <td><b>{{@$totalF}}</b></td>
            <td><b>{{@$totalStaff}}</b></td>
            <td><b>{{@$totalGbaseSalary}}</b></td>
            <td><b>{{@$totalGbasicSalary}}</b></td>
            <td><b>{{@$totalRetro}}</b></td>
            <td><b>{{@$totalDegree}}</b></td>
            <td><b>{{@$totalMonthlyAndTopUpSalary}}</b></td>
            <td><b>{{@$totalLocation}}</b></td>
            <td><b>{{@$totalMaternity}}</b></td>
            <td><b>{{@$totalLeaveWithoutPay}}</b></td>
            <td><b>{{@$totalSalaryBeforeTax}}</b></td>
            <td><b>{{@$totalSpouse}}</b></td>
            <td><b>{{@$totalChildren}}</b></td>
            <td><b>{{@$totalDependents}}</b></td>
            <td><b>{{@$totalSalaryTaxCalculationBase}}</b></td>
            <td><b>{{@$totalTaxOnSalary}}</b></td>
            <td><b>{{@$totalFringe}}</b></td>
            <td><b>{{@$totalTaxonFringe}}</b></td>
            <td><b>{{@$totalTaxPayable}}</b></td>
            <td><b>{{@$totalPayAfterTax}}</b></td>
            <td><b>{{@$totalSeniority}}</b></td>
            <td><b>{{@$totalStaffLoanDeduction}}</b></td>
            <td><b>{{@$totalPensionFund}}</b></td>
            <td><b>{{@$totalMMIDeduction}}</b></td>
            <td><b>{{@$totalOtherDeduction}}</b></td>
            <td><b>{{@$totalHalfMonth}}</b></td>
            <td><b>{{@$totalDeduction}}</b></td>
            <td><b>{{@$totalNetPay}}</b></td>
        </tr>
        </tbody>
    @endif
</table>