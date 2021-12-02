<table class="table table-striped">
        
    <tr>
        <th>No.</th>
        <th>Staff ID</th>
        <th>Staff Full Name KH</th>
        <th>Staff Full Name EN</th>
        <th>Gender</th>
        <th>Position</th>
        <th>Department/Branch</th>
        <th>Company</th>
        <th>Total Base Salary</th>
        <th>Retro Salary</th>
        <th>Pension Fund 5% Staff</th>
        <th>Pension Fund Company</th>
        <th>Interest Rate</th>
        <th>Salary Before Tax</th>
        <th>Tax on Salary</th>
        <th>Salary After Tax</th>

        <th>Spouse</th>
        <th>Gasoline</th>
        <th>Bonus Khmer New Year</th>
        <th>Bonus Pchum Ben</th>
        <th>Incentive</th>
        <th>Seniority</th>
        <th>Telephone</th>
        <th>Settlement</th>
        <th>Finger Print</th>
        <th>Compensation</th>
        <th>Leave Without Pay</th>
        <th>Motorcycle Rental</th>
        <th>Special Branch Allowance</th>

        <th>Half Pay</th>
        <th>Net Pay</th>
        <th>Posted Date</th>
    </tr>
    <tbody>

    @foreach($items as $key => $value)

        @php            
            $contract = $value->contract;
            $personal_info = @$value->staffPersonalInfo;
            $pensionFundInfo = @$value->json_data->pension_fund;
            $contractObj = @$contract->contract_object;

            if(@$value->json_data->less_dependents->amount){
                $spouse = @number_format(@$value->json_data->less_dependents->amount);
            }else{
                $spouse = 'N/A';
            }

            if(@$value->json_data->gasoline->total){
                $gasoline = @number_format(@$value->json_data->gasoline->amount);
            }else{
                $gasoline = 'N/A';
            }

            if(@$value->json_data->bonus_kny->amount){
                $bonusKny = @number_format(@$value->json_data->bonus_kny->amount);
            }else{
                $bonusKny = 'N/A';
            }

            if(@$value->json_data->bonus_pcb->amount){
                $bonusPcb = @number_format(@$value->json_data->bonus_pcb->amount);
            }else{
                $bonusPcb = 'N/A';
            }

            if(@$value->json_data->incentive->amount){
                $incentive = @number_format(@$value->json_data->incentive->amount);
            }else{
                $incentive = 'N/A';
            }

            if(@$value->json_data->seniority->amount){
                $seniority = @number_format(@$value->json_data->seniority->amount);
            }else{
                $seniority = 'N/A';
            }

            if(@$value->json_data->telephone->amount){
                $telephone = @number_format(@$value->json_data->telephone->amount);
            }else{
                $telephone = 'N/A';
            }

            if(@$value->json_data->settlement->amount){
                $settlement = @number_format(@$value->json_data->settlement->amount);
            }else{
                $settlement = 'N/A';
            }

            if(@$value->json_data->figer_print->amount){
                $fingerPrint = @number_format(@$value->json_data->figer_print->amount);
            }else{
                $fingerPrint = 'N/A';
            }

            if(@$value->json_data->compensation->amount){
                $compensation = @number_format(@$value->json_data->compensation->amount);
            }else{
                $compensation = 'N/A';
            }

            $leaveWithoutPayAmount = 0;
            foreach (@$value->json_data->leave_without_pay as $key => $leaveWithoutPay){
                $leaveWithoutPayAmount += $leaveWithoutPay->amount;
            }

            if(@$value->json_data->motorcycle_rental->amount){
                $motorcycleRental = @number_format(@$value->json_data->motorcycle_rental->amount);
            }else{
                $motorcycleRental = 'N/A';
            }

            if(@$value->json_data->specail_branch_alloance->amount){
                $specialBranchAllowance = @number_format(@$value->json_data->specail_branch_alloance->amount);
            }else{
                $specialBranchAllowance = 'N/A';
            }

        @endphp

        <tr>
            <td>{{ $key+=1 }}</td>
            <td>{{ @$personal_info->staff_id }}</td>
            <td>{{ @$personal_info->last_name_kh.' '.@$personal_info->first_name_kh }}</td>
            <td>{{ @$personal_info->last_name_en.' '.@$personal_info->first_name_en }}</td>
            <td>{{ @GENDER[@$personal_info->gender] }}</td>
            <td>{{ @$contractObj['position']['name_kh'] ?? "N/A" }}</td>
            <td>{{ @$contractObj['branch_department']['name_kh'] ?? "N/A" }}</td>
            <td>{{ @$contractObj['company']['name_kh'] ?? "N/A" }}</td>
            <td>{{ @number_format(@$value->json_data->total_base_salary) }}</td>
            <td>{{ @number_format(@$value->json_data->retro_salary->amount) }}</td>
            <td>{{ @$pensionFundInfo->acr_balance_staff ?: 'N/A' }}</td>
            <td>{{ @$pensionFundInfo->acr_balance_company ?: 'N/A' }}</td>
            <td>{{ @$pensionFundInfo->interest_rate * 100 . "%" }}</td>
            <td>{{ @$value->json_data->salary_before_tax ? @number_format(@$value->json_data->salary_before_tax) : 'N/A' }}</td>
            <td>{{ @$value->json_data->tax_on_salary ? @number_format(@$value->json_data->tax_on_salary) : 'N/A'}}</td>
            <td>{{ @$value->json_data->salary_after_tax ? @number_format(@$value->json_data->salary_after_tax) : 'N/A' }}</td>

            <td>{{ @$spouse }}</td>
            <td>{{ @$gasoline }}</td>
            <td>{{ @$bonusKny }}</td>
            <td>{{ @$bonusPcb }}</td>
            <td>{{ @$incentive }}</td>
            <td>{{ @$seniority }}</td>
            <td>{{ @$telephone }}</td>
            <td>{{ @$settlement }}</td>
            <td>{{ @$fingerPrint }}</td>
            <td>{{ @$compensation }}</td>
            <td>{{ @$leaveWithoutPayAmount ? @number_format(@$leaveWithoutPayAmount) : 'N/A' }}</td>
            <td>{{ @$motorcycleRental }}</td>
            <td>{{ @$specialBranchAllowance }}</td>

            <td>{{ @$value->json_data->half_pay->amount ? @number_format(@$value->json_data->half_pay->amount) : 'N/A' }}</td>
            <td>{{ @$value->json_data->net_pay ? @number_format(@$value->json_data->net_pay) : 'N/A' }}</td>
            <td>{{ date('d-M-Y', strtotime(@$value->created_at)) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>