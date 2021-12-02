@section('css')
    <style>
        input#staffProfile {
            display: none;
            position: absolute;
            top: 75px;
            width: 100px;
            padding: 45px 0 0 0;
            height: 28px;
            overflow: hidden;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
            background: url('/images/icon_upload.png') center center no-repeat #e4e4e4;
            background-size: 30px 30px;
        }
        .group-staff-profile:hover input#staffProfile {
            display: inline-block;
            cursor: pointer;
        }
        .group-staff-profile {
            border: 1px solid;
            overflow: hidden;
            width: 100px;
            height: 120px;
            position: relative;
        }
    </style>
@endsection

@section('personal_js')
    <script type="text/javascript" src="{{ asset('js/staff/index.js') }}"></script>
@stop

<div class="row">
    <div class="col-sm-12 col-md-12">
        <form action="{{ route('staff-personal-info.update') }}" role="form" method="post" id="edit_form_personal_info" enctype="multipart/form-data">

            <div class="row margin-bottom">
                <div class="col-sm-12 col-md-12">
                    <button type="submit" class="btn btn-success btn-sm margin-r-5" id="btnSave"><i class="fa fa-save"></i> UPDATE</button>
                    <button type="reset" class="btn btn-danger btn-sm" id="btnDiscard"><i class="fa fa-remove"></i> DISCARD</button>
                </div>
            </div>

            {{ csrf_field() }}
            <input type="hidden" name="staff_token" value="{{ encrypt($staff->id) }}">

            <div class="row">
                <div class="form-group col-sm-12 col-md-12">
                    <div class="pull-right group-staff-profile">
                        @if(!empty($staff->photo))
                            <img class="img-responsive" id="img-profile"
                                 src="{{ asset('images/staff/thumbnail/'.$staff->photo) }}"
                                 alt="Default image">
                        @else
                            <img class="img-responsive" id="img-profile"
                                 src="{{ asset('images/100x120.png') }}"
                                 alt="Default image">
                        @endif
                        <input type="file" id="staffProfile" name="staffProfile">
                    </div>
                </div>
            </div> <!-- /.row -->

            <div class="row">
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('last_name_kh')) has-error @endif">
                    <label for="last_name_kh">Last Name KH <span class="text-danger">*</span></label>
                    <input type="text" id="last_name_kh" name="last_name_kh" placeholder="{{ __('label.last_name_kh') }}" value="{{ $staff->last_name_kh }}">
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('first_name_kh')) has-error @endif">
                    <label for="first_name_kh">First Name KH <span class="text-danger">*</span></label>
                    <input type="text" id="first_name_kh" name="first_name_kh" placeholder="{{ __('label.first_name_kh') }}" value="{{ $staff->first_name_kh }}">
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('last_name_en')) has-error @endif">
                    <label for="last_name_en">Last Name EN <span class="text-danger">*</span></label>
                    <input type="text" id="last_name_en" name="last_name_en" placeholder="{{ __('label.last_name_en') }}" value="{{ $staff->last_name_en }}">
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('first_name_en')) has-error @endif">
                    <label for="first_name_en">First Name EN <span class="text-danger">*</span></label>
                    <input type="text" id="first_name_en" name="first_name_en" placeholder="{{ __('label.first_name_en') }}" value="{{ $staff->first_name_en }}">
                </div>
            </div> <!-- /.row -->

            <div class="row">
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('marital_status')) has-error @endif">
                    <label for="marital_status">Marital Status <span class="text-danger">*</span></label>
                    <select name="marital_status" id="marital_status" >
                        <option value="">>> {{ __('label.marital_status') }} <<</option>
                        @foreach($marital_status as $key => $value)
                            @if($staff->marital_status == $key)
                                <option value="{{ $key }}" selected>{{ $value }}</option>
                            @else
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('gender')) has-error @endif">
                    <label for="gender">Gender <span class="text-danger">*</span></label>
                    <select name="gender" id="gender" >
                        <option value="">>> {{ __('label.gender') }} <<</option>
                        @foreach($genders as $key => $value)
                            @if($staff->gender == $key)
                                <option value="{{ $key }}" selected>{{ $value }}</option>
                            @else
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                {{--<div class="form-group col-sm-6 col-md-3 @if($errors->has('id_type')) has-error @endif">
                    <label for="id_type">Identify Type <span class="text-danger">*</span></label>
                    <select name="id_type" id="id_type" >
                        <option value="">>> {{ __('label.identify_type') }} <<</option>
                        @foreach($idType as $key => $value)
                            @if($staff->id_type == $key)
                                <option value="{{ $key }}" selected>{{ $value }}</option>
                            @else
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>--}}
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('id_code')) has-error @endif">
                    <label for="id_code">National ID Card </label>
                    <input type="text" id="id_code" name="id_code" placeholder="{{ __('label.id_type_code') }}" value="{{ $staff->id_code }}">
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('dob')) has-error @endif">
                    <label for="date_of_birth">Date Of Birth <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="pull-right dob" id="date_of_birth" name="dob"
                               placeholder="{{ __('label.date_of_birth') }}"
                               value="{{ date('d-M-Y', strtotime($staff->dob)) }}" readonly>
                    </div>
                </div>
            </div> <!-- /.row -->

            <div class="row">
                <div class="form-group col-sm-6 col-md-12 @if($errors->has('pob')) has-error @endif">
                    <label for="pob">Place Of Birth <span class="text-danger">*</span></label>
                    <input type="text" name="pob" id="pob" placeholder="{{ __('label.place_of_birth') }}" value="{{ $staff->pob }}">
                </div>
            </div> <!-- ./row -->

            <div class="row">
                <div class="form-group col-sm-6 col-md-3">
                    <label for="bank_name">Bank Name </label>
                    <select name="bank_name" id="bank_name" >
                        <option value="">>> {{ __('label.bank_name') }} <<</option>
                        @foreach($bankNames as $key => $value)
                            @if($staff->bank_name == $key)
                                <option value="{{ $key }}" selected>{{ $value }}</option>
                            @else
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('bank_acc_no')) has-error @endif">
                    <label for="bank_acc_number">Bank Account Number </label>
                    <input type="text" name="bank_acc_no" id="bank_acc_number" placeholder="{{ __('label.bank_acc_number') }}"
                           value="{{ $staff->bank_acc_no }}">
                </div>
                <div class="form-group col-sm-6 col-md-3">
                    <label for="driver_license">Driver Licence </label>
                    <input type="text" name="driver_license" id="driver_license" placeholder="{{ __('label.driver_licence') }}" value="{{ $staff->driver_license }}">
                </div>
                <div class="form-group col-sm-6 col-md-3">
                    <label for="height">Height (cm)</label>
                    <input type="text"  name="height" id="height" placeholder="{{ __('label.height') }}" value="{{ $staff->height }}">
                </div>
            </div> <!-- /.row -->
            <div class="row">
                <div class="form-group col-sm-6 col-md-3 @if($errors->has('phone')) has-error @endif">
                    <label for="phone">Phone Number <span class="text-danger">*</span></label>
                    <input type="text"  name="phone" id="phone" placeholder="{{ __('label.mobile_no') }}" value="{{ $staff->phone }}">
                </div>
                <div class="form-group col-sm-6 col-md-3">
                    <label for="email">Email </label>
                    <input type="text"  name="email" id="email" placeholder="{{ __('label.email') }}" value="{{ $staff->email }}">
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Current Address</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-3">
                            <label for="province_id">Province / Town / City</label>
                            <select name="province_id" id="province_id" class="province_id js-select2-single form-control">
                                <option value="">>> {{ __('label.province') }} <<</option>
                                @foreach($provinces as $key => $value)
                                    @if($staff->province_id == $value->id)
                                        <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                    @else
                                        <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-3">
                            <label for="district_id">District </label>
                            <select name="district_id" id="district_id" class="district_id js-select2-single form-control">
                                <option value="">>> {{ __('label.district') }} <<</option>
                                @foreach($districts as $key => $value)
                                    @if($staff->district_id == $value->id)
                                        <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                    @else
                                        <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-3">
                            <label for="commune_id">Commune </label>
                            <select name="commune_id" id="commune_id" class="commune_id js-select2-single form-control">
                                <option value="">>> {{ __('label.commune') }} <<</option>
                                @foreach($communes as $key => $value)
                                    @if($staff->commune_id == $value->id)
                                        <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                    @else
                                        <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-3">
                            <label for="village_id">Village </label>
                            <select name="village_id" id="village_id" class="village_id js-select2-single form-control">
                                <option value="">>> {{ __('label.village') }} <<</option>
                                @foreach($villages as $key => $value)
                                    @if($staff->village_id == $value->id)
                                        <option value="{{ $value->id }}" selected>{{ $value->name_kh }}</option>
                                    @else
                                        <option value="{{ $value->id }}">{{ $value->name_kh }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div> <!-- /.row -->
                    <div class="row">
                        {{--<div class="form-group col-sm-6 col-md-3">
                            <label for="house_no">House number </label>
                            <input type="text"  name="house_no" id="house_no" placeholder="{{ __('label.house_no') }}" value="{{ $staff->house_no }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-3">
                            <label for="street_no">Street number </label>
                            <input type="text"  name="street_no" id="street_no" placeholder="{{ __('label.street_no') }}" value="{{ $staff->street_no }}">
                        </div>--}}
                        <div class="form-group col-sm-6 col-md-9">
                            <label for="other_location">Address Detail </label>
                            <input type="text"  name="other_location" id="other_location" placeholder="{{ __('label.other_location') }}" value="{{ $staff->other_location }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-3">
                            <label for="emergency_contact">Emergency Contact </label>
                            <input type="text"  name="emergency_contact" id="emergency_contact" placeholder="{{ __('label.emergency_contact') }}" value="{{ $staff->emergency_contact }}">
                        </div>

                    </div> <!-- /.row -->
                </div> <!-- /.panel boby -->
            </div> <!-- /.panel-default -->
            <div class="row">
                <div class="form-group col-sm-12 col-md-12">
                    <label for="noted">Note </label>
                    <textarea class="form-control" name="noted" id="noted" rows="3" placeholder="{{ __('label.noted') }}">{{ $staff->noted }}</textarea>
                </div>
            </div>
        </form> <!-- /.form -->

    </div> <!-- /.col-12 -->
</div> <!-- /.row -->