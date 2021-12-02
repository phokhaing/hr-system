
<table>
    <thead>
    <tr>
    <th>Staff ID</th>
        <th>Name In English</th>
        <th>Position</th>
        <th>Location</th>
        <th>D.O.E</th>
        <th>Effective Date</th>
        <th>Gross Base Salary</th>
        <th>Overtime</th>
        <th>Pchum Ben and New Year Bonus</th>
        <th>Incentive</th>
        <th>Location Allowance</th>
        <th>Food Allowance</th>
        <th>Third Salary Bonus</th>
        <th>Degree Allowance</th>
        <th>Position Allowance</th>
        <th>Attendance Allowance</th>
        <th>Retroactive Salary</th>
        <th>Seniority Pay</th>
        <th>UnPaid Leave</th>
        <th>Fringe Allowance</th>
        <th>Tax on Fringe</th>
        <th>Spouse</th>
        <th>Salary Before Tax</th>
        <th>Tax on Salary</th>
        <th>Total Tax Payable</th>
        <th>Salary After Tax</th>
        <th>Staff Loan Paid</th>
        <th>Insurance Pay</th>
        <th>Maternity Leave</th>
        <th>Total Salary Deduction</th>
        <th>Pension Fund</th>
        <th>NSSF</th>
        <th>Half Month</th>
        <th>Net Salary</th>
    </tr>
    </thead>
    @if(isset($temp_payrolls))
        <tbody>
        @foreach($temp_payrolls as $key => $payroll)
            @php
                $nameInEnglish = @$payroll->last_name_en . ' ' . @$payroll->first_name_en;
                $fringeTax = @$payroll->tax_on_fringe_allowance;
            @endphp
            <tr>
            <td>{{ @$payroll->staff_id }}</td>
                <td>{{ @$nameInEnglish }}</td>
                <td>{{ @$payroll->position }}</td>
                <td>{{ @$payroll->branch_department }}</td>
                <td>{{ date('d-M-Y', strtotime(@$payroll->start_date)) }}</td>
                <td>{{ date('d-M-Y', strtotime(@$payroll->start_date)) }}</td>
                
                <td>{{ @convertSalaryFromStrToFloatValue(@$payroll->base_salary) }}</td>
                
                <td>{{ @$payroll->overtime }}</td>
                <td>{{ @$payroll->pchumben_and_newyear_bonus }}</td>
                <td>{{ @$payroll->incentive }}</td>
                <td>{{ @$payroll->location_allowance }}</td>
                <td>{{ @$payroll->food_allowance }}</td>
                <td>{{ @$payroll->third_salary_bonus }}</td>
                <td>{{ @$payroll->degree_allowance }}</td>
                <td>{{ @$payroll->position_allowance }}</td>
                <td>{{ @$payroll->attendance_allowance }}</td>
                <td>
                    {{ @$payroll->retroactive_salary }}
                </td>
                <td>{{ @$payroll->seniority_pay }}</td>
                <td>{{ @$payroll->unpaid_leave }}</td>
                <td>{{ @$payroll->fringe_allowance }}</td>
                <td>{{ @$fringeTax }}</td>
                <td>{{ @$payroll->spouse }}</td>
                
                <td>{{ @$payroll->salary_before_tax }}</td>
                <td>{{ @$payroll->tax_on_salary }}</td>
                <td>{{ @$payroll->total_tax_payable }}</td>
                <td>{{ @$payroll->salary_after_tax }}</td>
                
                <td>{{ @$payroll->staff_loan_paid }}</td>
                <td>{{ @$payroll->insurance_pay }}</td>
                <td>{{ @$payroll->maternity_leave }}</td>
                <td>{{ @$payroll->salary_deduction }}</td>
                <td>{{ @$payroll->pension_fund }}</td>
                <td>{{ @$payroll->nssf }}</td>
                
                <td>{{ @$payroll->half_salary }}</td>
                <td>{{ @$payroll->net_salary }}</td>
            </tr>
        @endforeach
        </tbody>
    @endif
</table>