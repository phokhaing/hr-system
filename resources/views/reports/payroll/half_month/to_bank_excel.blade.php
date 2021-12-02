<table style="width: 100%; border-color: black;">
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <td>
            <b>No.</b>
        </td>
        <td>
            <b>Account Name</b>
        </td>
        <td>
            <b>Account Number</b>
        </td>
        <td>
            <b>Sex</b>
        </td>
        <td>
            <b>Salary</b>
        </td>
        <td>
            <b>Bank Charge</b>
        </td>
        <td>
            <b>Value Date</b>
        </td>
    </tr>
    @if(!is_null($items) && count($items))
        @php
            $index = 0;
            $totalSalary = 0;
            $totalBankCharge = 0;
        @endphp
        @foreach($items as $key => $value)
            <?php
            $index++;
            $staffPersonalInfo = @$value->staff_personal_info;
            $totalSalary += @$value->transaction_object->amount;
            $totalBankCharge += 800;
            ?>
            <tr>
                <td>{{ $index }}</td>
                <td>{{ @$staffPersonalInfo->last_name_en . ' ' .@$staffPersonalInfo->first_name_en  }}</td>
                <td>{{ @$staffPersonalInfo->bank_acc_no  }}</td>
                <td>{{ @$staffPersonalInfo->gender == 1 ? 'F' : 'M'  }}</td>
                <td>{{ @$value->transaction_object->amount  }}</td>
                <td style="text-align: left">800</td>
                <td>{{ date('m/d/Y', strtotime(@$value->transaction_date)) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4">
                <b>Total</b>
            </td>
            <td style="text-align: center">
                <b>
                    {{ @$totalSalary }}
                </b>
            </td>
            <td>
                <b>
                    {{ @$totalBankCharge }}
                </b>
            </td>
            <td></td>
        </tr>
    @endif
</table>