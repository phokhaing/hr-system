@extends('staffs.edit')

@section('tab')
    <div class="tab-pane active" id="pension-fund">

        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-striped">
                            <tbody>
                            <tr>
                                <th>1</th>
                                <th>Total Amount for Staff(5%)</th>
                                <th>
                                    {{number_format(@$staff->currentPensionFund->json_data->acr_balance_staff, 2) . ' '. @$contractCurrency->currency}}
                                </th>
                            </tr>

                            <tr>
                                <th>2</th>
                                <th>Total Amount From Company(5%)</th>
                                <th>
                                    {{number_format(@$totalPf['total_acr_company'], 2) . ' '. @$contractCurrency->currency}}
                                </th>
                            </tr>

                            <tr>
                                <th>3</th>
                                <th>Balance tobe Paid</th>
                                <th>
                                    {{number_format(@$totalPf['balance_to_paid'], 2). ' '. @$contractCurrency->currency}}
                                </th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection