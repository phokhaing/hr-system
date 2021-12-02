<table class="table table-striped">
    <thead>
    <tr>
        <th>No</th>
        <th>Staff ID Card</th>
        <th>Staff Name</th>
        <th>Employment Date</th>
        <th>Last Working Date</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach(@$items as $key => $claim)
        @php
            $claimObj = $claim->json_data;
        @endphp
        <tr>
            <td>{{ $key += 1 }}</td>
            <td>{{ $claimObj->net_pay }}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>