<form action="{{ route('report.staffEndContract') }}" role="form" method="get" id="report_contract">
    {{ csrf_field() }}
    <input type="hidden" name="is_download" id="is_download">
    <input type="hidden" name="is_report_end_contract" value="1">
    <div class="row">
        {{--<div class="form-group col-sm-6 col-md-6">
            <input type="text" name="key_word" class="form-control"
                   placeholder="Type keyword... Staff ID, Name, Phone"
                   value="{{ request('key_word') }}">
        </div>--}}

        <div class="form-group col-sm-6 col-md-6">
           {{-- <button type="submit" class="btn btn-primary margin-r-5" id="searchBtn">
                <i class="fa fa-search"></i> Search
            </button>

            <button type="button" data-toggle="collapse" class="btn btn-primary margin-r-5" data-target="#advanceFilter" aria-expanded="false" aria-controls="advanceFilter">
                <i class="fa fa-search-plus"></i> Advance Filter
            </button>--}}

        </div>
    </div> <!-- .row -->
    <div id="advanceFilter">
        <div class="panel panel-danger">
            <div class="panel-body">
                <div class="row">
                    <div class="form-group col-sm-6 col-md-6 col-lg-6">
                        <label for="contract_type">Select End Contract Type</label>
                        <select class="form-control contract_type" name="contract_type">
                            <option value="" selected>>> {{ __('End contract type') }} <<</option>
                            {!! SELECT_ALL !!}
                            @foreach(CONTRACT_END_TYPE as $key => $value)
                                <option data-key="{{$key}}" value="{{$value}}" {{$value== request(
                                        'contract_type') ? 'selected' : ''}}>{{ $key }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6 col-md-6 col-lg-6">
                        <label for="contract_start_date">Contract Start Date</label>
                        <input type="text" class="form-control pull-right contract_start_date"
                               id="contract_start_date" name="contract_start_date"
                               value="{{ request('contract_start_date') }}" readonly
                               placeholder="{{ __('label.contract_start_date') }}">

                    </div>
                    <div class="form-group col-sm-6 col-md-6 col-lg-6">
                        <label for="contract_end_date">Contract End Date <span
                                    class="text-danger">*</span></label>
                        <input type="text" class="form-control pull-right contract_end_date"
                               id="contract_end_date" name="contract_end_date"
                               value="{{ request('contract_end_date') }}" readonly
                               placeholder="{{ __('label.contract_end_date') }}">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-12 col-md-6 col-lg-6">
                        <label for="company_code">Company</label>
                        <select id="company_code" name="company_code" class="form-control company_code select2">
                            <option value="">>> {{ __('label.company') }} <<</option>
                            {!! SELECT_ALL !!}
                            @foreach($companies as $key => $value)
                                <option value="{{ $value->company_code }}" {{request('company_code') == $value->company_code ? 'selected' : ''}}>{{ $value->name_kh }} ({{ $value->short_name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-12 col-md-6 col-lg-6">
                        <label for="branch_department_code">Department or Branch </label>
                        <select id="branch_department_code" name="branch_department_code"
                                class="form-control branch_department_code select2">
                            <option value=""> << {{__('label.department_or_branch') }} >></option>
                        </select>
                    </div>

                    <div class="form-group col-sm-12 col-md-6 col-lg-6">
                        <label for="position_code">Position</label>
                        <select id="position_code" name="position_code" class="form-control position_code select2">
                            <option value="">>> {{ __('label.position') }} <<</option>
                        </select>
                    </div>

                    <div class="form-group col-sm-12 col-md-6 col-lg-6">
                        <label for="gender">Gender</label>
                        <select id="gender" name="gender" class="form-control">
                            <option value="" selected>>> {{ __('label.gender') }} <<</option>
                            {!! SELECT_ALL !!}
                            @foreach(GENDER as $key => $value)
                                @if(request('gender') == $key.'')
                                    <option value="{{$key}}" selected>{{$value}}</option>
                                @else
                                    <option value="{{$key}}">{{$value}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 col-md-6">
                        <button type="submit" class="btn btn-primary margin-r-5" id="searchBtn">
                            <i class="fa fa-search"></i> Search
                        </button>
                        <button type="submit" class="btn btn-success margin-r-5" id="downloadBtn">
                            <i class="fa fa-download"></i> Download
                        </button>
                       {{-- <button type="reset" class="btn btn-danger" id="resetBtn"><i class="fa fa-remove"></i> Reset
                        </button>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>