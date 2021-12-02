@extends('adminlte::page')

@section('title', 'List of staff')

@section('css')
    <style>
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

        label {
            color: #0d6aad;
        }
    </style>
@endsection

@section('content')
    <ul class="breadcrumb">
        <li><a href="/"> Dashboard</a></li>
        <li><a href="{{ route('staff-personal-info.index') }}"> Staff-Personal-Info</a></li>
        <li>  {{ @$staff->last_name_kh.' '.@$staff->first_name_kh }}  </li>
    </ul>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-bold">Personal Information</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12">
                            <div class="pull-right group-staff-profile">
                                @if(!empty(@$staff->photo))
                                    <img class="img-responsive" id="img-profile"
                                         src="{{ asset('images/staff/thumbnail/'.@$staff->photo) }}"
                                         alt="Default image">
                                @else
                                    <img class="img-responsive" id="img-profile"
                                         src="{{ asset('images/100x120.png') }}"
                                         alt="Default image">
                                @endif
                            </div>
                        </div>
                    </div> <!-- /.row -->
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Full Name KH : </label>
                            <p>{{ @$staff->last_name_kh.' '.@$staff->first_name_kh }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Full Name EN : </label>
                            <p>{{ @$staff->last_name_en.' '.@$staff->first_name_en }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Marital Status : </label>
                            <p>{{ $data_selected->marital_status->name_kh ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Gender : </label>
                            @if(@$staff->gender == 0)
                                <p>Male</p>
                            @endif
                            @if(@$staff->gender == 1)
                                <p>Female</p>
                            @endif
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">National ID Card : </label>
                            <p>{{ @$staff->id_code ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Date of Employment : </label>
                            <p>{{ @@$staff->firstContract->start_date ? date('d-M-Y', strtotime(@@$staff->firstContract->start_date)) : 'N/A' }}</p>
                        </div>
                    </div> <!-- /.row -->
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Date Of Birth : </label>
                            <p>{{ date('d-M-Y', strtotime(@$staff->dob)) ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Place Of Birth : </label>
                            <p>{{ empty(@$staff->pob) ? 'N/A' : @$staff->pob }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Bank Name : </label>
                            <p>{{ empty($data_selected->bank_name->name_kh) ? 'N/A' : $data_selected->bank_name->name_kh }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Bank Account Number : </label>
                            <p>{{ empty(@$staff->bank_acc_no) ? 'N/A' : @$staff->bank_acc_no }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Height : </label>
                            <p>{{ @$staff->height?? 'N/A' }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Driver License : </label>
                            <p>{{ empty(@$staff->driver_license) ? 'N/A' : @$staff->driver_license }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Province : </label>
                            <p>{{  empty($data_selected->province->name_kh) ? 'N/A' : $data_selected->province->name_kh }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">District : </label>
                            <p>{{  empty($data_selected->district->name_kh) ? 'N/A' : $data_selected->district->name_kh }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Commune : </label>
                            <p>{{  empty($data_selected->commune->name_kh) ? 'N/A' : $data_selected->commune->name_kh }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Village : </label>
                            <p>{{ empty($data_selected->village->name_kh) ? 'N/A' : $data_selected->village->name_kh }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Address Detail : </label>
                            <p>{{  empty(@$staff->other_location) ? 'N/A' : @$staff->other_location }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Email : </label>
                            <p>{{  empty(@$staff->email) ? 'N/A' : @$staff->email }}</p>
                        </div>
                    </div> <!-- /.row -->
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Phone Number : </label>
                            <p>{{  empty(@$staff->phone) ? 'N/A' : @$staff->phone }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Emergency Contact : </label>
                            <p>{{ empty(@$staff->emergency_contact) ? 'N/A' : @$staff->emergency_contact }}</p>
                        </div>
                        <div class="form-group col-sm-8 col-md-6">
                            <label for="">Note : </label>
                            <p>{{ empty(@$staff->noted) ? 'N/A' : @$staff->noted }}</p>
                        </div>
                    </div> <!-- .row -->
                    <hr style="border: 1px solid #3c8dbc;">
                </div> <!-- /.panel-body -->
            </div> <!-- /.panel panel-default -->

            @if(@$staff->educations && @count(@$staff->educations) > 0)
                <div class="panel panel-default" style="overflow-x: auto;">
                    <div class="panel-heading text-bold">Educations</div>
                    <table class="table table-striped">
                        <tr class="bg-gray-active">
                            <th>No.</th>
                            <th>School Nname</th>
                            <th>Subject</th>
                            <th>Degree</th>
                            <th>Study Year</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Province</th>
                            <th>Other Location</th>
                            <th>Note</th>
                        </tr>
                        @foreach(@$staff->educations as $key => $education)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$education->school_name ?? 'N/A'}}</td>
                                <td>{{$education->subject ?? 'N/A'}}</td>
                                <td>
                                    {{empty($education->degree_id) ? 'N/A' : \App\Unity::showDegree($education->degree_id)}}
                                </td>
                                <td>
                                    {{empty($education->study_year) ? 'N/A' : \App\Unity::showStudyYear($education->study_year)}}
                                </td>
                                <td>
                                    {{empty($education->start_date) ? 'N/A' : date('d-M-Y', strtotime($education->start_date)) }}
                                    }
                                </td>
                                <td>
                                    {{empty($education->end_date) ? 'N/A' : date('d-M-Y', strtotime($education->end_date))}}
                                </td>
                                <td>
                                    {{empty($education->province_id) ? 'N/A' : \App\Unity::showProvince($education->province_id)}}
                                </td>
                                <td>
                                    {{empty($education->other_location) ? 'N/A' : $education->other_location}}
                                </td>
                                <td>
                                    {{$education->noted ?? 'N/A'}}
                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div> <!-- /.panel panel-default -->
            @endif

            @if(@$staff->trainings && @count(@$staff->trainings) > 0)
                <div class="panel panel-default" style="overflow-x: auto;">
                    <div class="panel-heading text-bold">Trainings</div>
                    <table class="table table-striped">
                        <tr class="bg-gray-active">
                            <th>No.</th>
                            <th>Subject Name</th>
                            <th>School / Institute / University</th>
                            <th>Province</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Training Place</th>
                            <th>Other Location</th>
                            <th>Description</th>
                        </tr>

                        @foreach(@$staff->trainings as $key => $training)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{empty($training->subject) ? 'N/A' : $training->subject}}</td>
                                <td>{{ empty($training->school) ? 'N/A' : $training->school }}</td>
                                <td>
                                    @if($training->province_id)
                                        {{ \App\Unity::showProvince($training->province_id) }}
                                    @endif
                                    N/A
                                </td>
                                <td>{{ empty($training->start_date) ? 'N/A' : date('d-M-Y', strtotime($training->start_date)) }}</td>
                                <td>{{empty($training->end_date) ? 'N/A' : date('d-M-Y', strtotime($training->end_date))}}</td>
                                <td>{{empty($training->place) ? 'N/A' : $training->place}}</td>
                                <td>{{empty($training->other_location) ? 'N/A' : $training->other_location}}</td>
                                <td>{{empty($training->description) ? 'N/A' : $training->description}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div> <!-- /.panel panel-default -->
            @endif

            @if(@$staff->experiences && @count(@$staff->experiences) > 0)
                <div class="panel panel-default" style="overflow-x: auto;">
                    <div class="panel-heading text-bold">Experiences</div>
                    <table class="table table-striped">
                        <tr class="bg-gray-active">
                            <th>No.</th>
                            <th>Company Name EN</th>
                            <th>Company Name KH</th>
                            <th>Position</th>
                            <th>Level</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Province / Town / City</th>
                            <th>House Number</th>
                            <th>Street Number</th>
                            <th>Other Location</th>
                            <th>Note</th>
                        </tr>
                        @foreach(@$staff->experiences as $experience)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{empty($experience->company_name_en) ? 'N/A' : $experience->company_name_en}}</td>
                                <td>{{empty($experience->company_name_kh) ? 'N/A' : $experience->company_name_kh}}</td>
                                <td>{{empty($experience->position) ? 'N/A' : $experience->position}}</td>
                                <td>{{empty($experience->level_position) ? 'N/A' : $experience->level_position}}</td>
                                <td>{{empty($experience->start_date) ? 'N/A' : date('d-M-Y', strtotime($experience->start_date))}}</td>
                                <td>{{empty($experience->end_date) ? 'N/A' : date('d-M-Y', strtotime($experience->end_date))}}</td>
                                <td>{{empty($experience->province) ? "N/A" : \App\Unity::showProvince($experience->province)}}</td>
                                <td>{{empty($experience->house_no) ? 'N/A' : $experience->house_no}}</td>
                                <td>{{empty($experience->street_no) ? 'N/A' : $experience->street_no}}</td>
                                <td>{{empty($experience->other_location) ? 'N/A' :$experience->other_location}}</td>
                                <td>{{empty($experience->noted) ? 'N/A' : $experience->noted}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div> <!-- /.panel panel-default -->
            @endif

            @if(@$staff->spouse)
                @php
                    $spouse = @$staff->spouse;
                @endphp
                <div class="panel panel-default" style="overflow-x: auto;">
                    <div class="panel-heading text-bold">Spouse</div>
                    <table class="table table-striped">
                        <tr class="bg-gray-active">
                            <th>No.</th>
                            <th>Spouse Full Name</th>
                            <th>Gender</th>
                            <th>Date Of Birth</th>
                            <th>Child Number</th>
                            <th>Child Tax</th>
                            <th>Spouse Tax</th>
                            <th>Province</th>
                            <th>District</th>
                            <th>Commune</th>
                            <th>Village</th>
                            <th>House Number</th>
                            <th>Street Number</th>
                            <th>Phone Number</th>
                            <th>Occupation</th>
                            <th>Other Location</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>{{@$spouse->full_name??'N/A'}}</td>
                            <td>
                                @if(@$spouse->gender == 1)
                                    Male
                                @elseif(@$spouse->gender == 0)
                                    Female
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                {{ @$spouse->dob ? 'N/A' : date('d-M-Y', strtotime(@$spouse->dob))}}
                            </td>
                            <td>
                                {{@$spouse->children_no??'N/A'}}
                            </td>
                            <td>
                                {{@$spouse->children_tax ?? 'N/A'}}
                            </td>
                            <td>
                                @if(@$spouse->spouse_tax == 0)
                                    Exclude
                                @elseif(@$spouse->spouse_tax == 1)
                                    Include
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                @if(@$spouse->province_id)
                                    {{ \App\Unity::showProvince(@$spouse->province_id) }}
                                @endif
                                N/A
                            </td>
                            <td>
                                @if(@$spouse->district_id)
                                    {{ \App\Unity::showDistrict(@$spouse->district_id) }}
                                @endif
                                N/A
                            </td>
                            <td>
                                @if(@$spouse->commune_id)
                                    {{ \App\Unity::showCommune(@$spouse->commune_id) }}
                                @endif
                                N/A
                            </td>
                            <td>
                                @if(@$spouse->village_id)
                                    {{ \App\Unity::showVillage(@$spouse->village_id) }}
                                @endif
                                N/A
                            </td>
                            <td>
                                {{@$spouse->house_no ?? 'N/A'}}
                            </td>
                            <td>
                                {{@$spouse->street_no ?? 'N/A'}}
                            </td>
                            <td>
                                {{@$spouse->phone ?? 'N/A'}}
                            </td>
                            <td>
                                @if(@$spouse->occupation_id)
                                    {{ \App\Unity::showOccupation(@$spouse->occupation_id) }}
                                @endif
                                N/A
                            </td>
                            <td>
                                {{@$spouse->other_location}}
                            </td>
                        </tr>

                    </table>
                </div> <!-- /.panel panel-default -->
            @endif

            @if(@$staff->guarantors && @count(@$staff->guarantors) > 0)
                <div class="panel panel-default" is="" style="overflow-x: auto;">
                    <div class="panel-heading text-bold">Guarantors</div>

                    <table class="table table-striped">
                        <tr class="bg-gray-active">
                            <th>No.</th>
                            <th>Guarantor Name EN</th>
                            <th>Guarantor Name KH</th>
                            <th>Gender</th>
                            <th>Identify Type</th>
                            <th>Identify Code</th>
                            <th>Marital Status</th>
                            <th>Province</th>
                            <th>District</th>
                            <th>Commune</th>
                            <th>Village</th>
                            <th>House Number</th>
                            <th>Street Number</th>
                            <th>Occupation</th>
                            <th>Date Of Birth</th>
                            <th>Place Of Birth</th>
                            <th>Mobile Number</th>
                            <th>Email Address</th>
                            <th>Other Location</th>
                        </tr>
                        @foreach(@$staff->guarantors as $key => $guarantor)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$guarantor->last_name_en." ".$guarantor->first_name_en}}</td>
                                <td>{{$guarantor->last_name_kh." ".$guarantor->first_name_kh}}</td>
                                <td>
                                    @if($guarantor->gender == 0)
                                        Male
                                    @elseif($guarantor->gender == 1)
                                        Female
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    {{empty($guarantor->id_type) ? 'N/A' : \App\Unity::showIdType($guarantor->id_type)}}
                                </td>
                                <td>{{empty($guarantor->id_code) ? 'N/A' : $guarantor->id_code}}</td>
                                <td>{{$data_selected->guarantor[$key]->marital_status ?? 'N/A'}}</td>
                                <td>
                                    @if($guarantor->province_id)
                                        {{ \App\Unity::showProvince($guarantor->province_id) }}
                                    @endif
                                </td>
                                <td>
                                    @if($guarantor->district_id)
                                        {{ \App\Unity::showDistrict($guarantor->district_id) }}
                                    @endif
                                </td>
                                <td>
                                    @if($guarantor->commune_id)
                                        {{ \App\Unity::showCommune($guarantor->commune_id) }}
                                    @endif
                                </td>
                                <td>
                                    @if($guarantor->village_id)
                                        {{ \App\Unity::showVillage($guarantor->village_id) }}
                                    @endif
                                </td>
                                <td>{{empty($guarantor->house_no) ? 'N/A' : $guarantor->house_no}}</td>
                                <td>{{empty($guarantor->street_no) ? 'N/A' : $guarantor->street_no}}</td>
                                <td>

                                    @if($guarantor->occupation_id)
                                        {{ \App\Unity::showOccupation($guarantor->occupation_id) }}
                                    @endif
                                    N/A
                                </td>
                                <td>{{empty($guarantor->dob) ? 'N/A' : date('d-M-Y', strtotime($guarantor->dob)) }}</td>
                                <td>{{empty($guarantor->pob) ? 'N/A' : $guarantor->pob}}</td>
                                <td>{{empty($guarantor->phone) ? 'N/A' : $guarantor->phone}}</td>
                                <td>{{empty($guarantor->email) ? 'N/A' : $guarantor->email}}</td>
                                <td>{{empty($guarantor->other_location) ? 'N/A' : $guarantor->other_location}}</td>
                            </tr>
                        @endforeach

                    </table>
                </div> <!-- /.panel panel-default -->
            @endif

            @if(@$staff->documents && @count(@$staff->documents) > 0)
                <div class="panel panel-default" style="overflow-x: auto;">
                    <div class="panel-heading text-bold">Documents</div>
                    <table class="table table-striped">
                        <tr class="bg-gray-active">
                            <th>No.</th>
                            <th>Document type</th>
                            <th>Action</th>
                        </tr>
                        @foreach(@$staff->documents as $key => $document)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$data_selected->document[$key]->document_name ?? 'N/A'}}</td>
                                <td>
                                    @if(empty($document->src))
                                        N/A
                                    @else
                                        <a target="_blank" href="{{ asset($document->src) }}"
                                           class="btn btn-default btn-sm"><i class="fa fa-file"></i> Read file</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach

                    </table>

                </div> <!-- /.panel panel-default -->
            @endif

        </div> <!-- /.col-sm-12 col-md-12 -->
    </div> <!-- /.row -->
@stop

@section('js')
    <script type="text/javascript" src="{{ asset('js/staff/index.js') }}"></script>
@stop