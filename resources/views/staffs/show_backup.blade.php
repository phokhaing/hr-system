@extends('adminlte::page')

@section('title', 'List of staff')

@section('css')
    <style>
        label {
            color: #0d6aad;
        }
    </style>
@endsection

@section('content')
    <ul class="breadcrumb">
        <li> <a href="/"> Dashboard</a> </li>
        <li> <a href="{{ route('staff-personal-info.index') }}"> Staff-personal-info</a> </li>
        <li>  {{ $staff->last_name_kh.' '.$staff->first_name_kh }}  </li>
    </ul>

    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="breadcrumb">
{{--                <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> IMPORT</a>--}}
                <a href="{{ route('staff-personal-info.create') }}#personal_info" class="btn btn-success btn-sm"><i class="fa fa-user-plus"></i> CREATE</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-bold">Personal information</div>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Full name KH : </label>
                            <p>{{ $staff->last_name_kh.' '.$staff->first_name_kh }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Full name EN : </label>
                            <p>{{ $staff->last_name_en.' '.$staff->first_name_en }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Marital status : </label>
                            <p>{{ $data_selected->marital_status->name_kh ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Gender : </label>
                            @if($staff->gender == 0)
                                <p>Male</p>
                            @endif
                            @if($staff->gender == 1)
                                <p>Female</p>
                            @endif
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">ID type : </label>
                            <p>{{ $data_selected->id_type->name_kh ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">ID Code : </label>
                            <p>{{ $staff->id_code ?? 'N/A' }}</p>
                        </div>
                    </div> <!-- /.row -->
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Date of birth : </label>
                            <p>{{ date('d-M-Y', strtotime($staff->dob)) ?? 'N/A' }}</p>
                        </div>
                        <div class="form-group col-sm-8 col-md-6">
                            <label for="">Place of birth : </label>
                            <p>{{ empty($staff->pob) ? 'N/A' : $staff->pob }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Bank name : </label>
                            <p>{{ empty($data_selected->bank_name->name_kh) ? 'N/A' : $data_selected->bank_name->name_kh }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Bank account number : </label>
                            <p>{{ empty($staff->bank_acc_no) ? 'N/A' : $staff->bank_acc_no }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Height : </label>
                            <p>{{ $staff->height }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Driver License : </label>
                            <p>{{ empty($staff->driver_license) ? 'N/A' : $staff->driver_license }}</p>
                        </div>
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
                    </div> <!-- /.row -->
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">House number : </label>
                            <p>{{ empty($staff->house_no) ? 'N/A' : $staff->house_no }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Street number : </label>
                            <p>{{  empty($staff->street_no) ? 'N/A' : $staff->street_no }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Email address : </label>
                            <p>{{  empty($staff->email) ? 'N/A' : $staff->email }}</p>
                        </div>
                        <div class="form-group col-sm-8 col-md-6">
                            <label for="">Other location : </label>
                            <p>{{  empty($staff->other_location) ? 'N/A' : $staff->other_location }}</p>
                        </div>
                    </div> <!-- /.row -->
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Phone number : </label>
                            <p>{{  empty($staff->phone) ? 'N/A' : $staff->phone }}</p>
                        </div>
                        <div class="form-group col-sm-6 col-md-2">
                            <label for="">Emergency contact : </label>
                            <p>{{ empty($staff->emergency_contact) ? 'N/A' : $staff->emergency_contact }}</p>
                        </div>
                        <div class="form-group col-sm-8 col-md-6">
                            <label for="">Emergency contact : </label>
                            <p>{{ empty($staff->noted) ? 'N/A' : $staff->noted }}</p>
                        </div>
                    </div> <!-- .row -->
                    <hr style="border: 1px solid #3c8dbc;">
                </div> <!-- /.panel-body -->
            </div> <!-- /.panel panel-default -->

            <div class="panel panel-default" style="overflow-x: auto;">
                <div class="panel-heading text-bold">Personal information</div>
                <table class="table table-striped">
                    <tr class="bg-gray-active">
                        <th>Full name KH</th>
                        <th>Full name EN</th>
                        <th>Marital status</th>
                        <th>Gender</th>
                        <th>ID type</th>
                        <th>ID Code</th>
                        <th>Date of birth</th>
                        <th>Place of birth</th>
                        <th>Bank name</th>
                        <th>Bank account number</th>
                        <th>Height</th>
                        <th>Driver License</th>
                        <th>Province</th>
                        <th>District</th>
                        <th>Commune</th>
                        <th>Village</th>
                        <th>Address Detail</th>
                        <th>Email address</th>
                        <th>Phone number</th>
                        <th>Emergency contact</th>
                        <th>Emergency note</th>
                    </tr>
                    <tr>
                        <td>{{$staff->last_name_kh.' '.$staff->first_name_kh}}</td>
                        <td>{{$staff->last_name_en.' '.$staff->first_name_en}}</td>
                        <td>{{$data_selected->marital_status->name_kh ?? 'N/A'}}</td>
                        <td>
                            @if($staff->gender == 0)
                            Male
                            @endif
                            @if($staff->gender == 1)
                            Female
                            @endif
                        </td>
                        <td>{{$data_selected->id_type->name_kh ?? 'N/A'}}</td>
                        <td>{{$staff->id_code ?? 'N/A'}}</td>
                        <td>{{date('d-M-Y', strtotime($staff->dob)) ?? 'N/A'}}</td>
                        <td>{{empty($staff->pob) ? 'N/A' : $staff->pob}}</td>
                        <td>{{$data_selected->bank_name->name_kh ?? 'N/A'}}</td>
                        <td>{{$staff->bank_acc_no ?? 'N/A'}}</td>
                        <td>{{$staff->height ?? 'N/A'}}</td>
                        <td>{{$staff->driver_license ?? 'N/A'}}</td>
                        <td>{{$data_selected->province->name_kh ?? 'N/A'}}</td>
                        <td>{{$data_selected->district->name_kh ?? 'N/A'}}</td>
                        <td>{{$data_selected->commune->name_kh ?? 'N/A'}}</td>
                        <td>{{$data_selected->village->name_kh ?? 'N/A'}}</td>
                        <td>{{$staff->other_location ?? 'N/A'}}</td>
                        <td>{{$staff->email ?? 'N/A'}}</td>
                        <td>{{$staff->phone ?? 'N/A'}}</td>
                        <td>{{$staff->emergency_contact ?? 'N/A'}}</td>
                        <td>{{$staff->emergency_noted ?? 'N/A'}}</td>
                    </tr>

                </table>
            </div> <!-- /.panel panel-default -->

            @if(count($staff->educations) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading text-bold">Educations</div>
                    @foreach($staff->educations as $key => $education)
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">School name : </label>
                                    <p>{{ empty($education->school_name) ? 'N/A' : $education->school_name }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Subject : </label>
                                    <p>{{ empty($education->subject) ? 'N/A' : $education->subject }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Degree : </label>
                                    <p>{{ empty($education->degree_id) ? 'N/A' : \App\Unity::showDegree($education->degree_id) }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Study Year : </label>
                                    <p>{{ empty($education->study_year) ? 'N/A' : \App\Unity::showStudyYear($education->study_year) }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Start date : </label>
                                    <p>{{ empty($education->start_date) ? 'N/A' : date('d-M-Y', strtotime($education->start_date)) }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">End date : </label>
                                    <p>{{ empty($education->end_date) ? 'N/A' : date('d-M-Y', strtotime($education->end_date)) }}</p>
                                </div>
                            </div> <!-- row -->
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Province : </label>
                                    <p>{{ empty($education->province_id) ? 'N/A' : \App\Unity::showProvince($education->province_id) }}</p>
                                </div>
                                <div class="form-group col-sm-12 col-md-10">
                                    <label for="">Other location : </label>
                                    <p>{{ empty($education->other_location) ? 'N/A' : $education->other_location }}</p>
                                </div>
                                <div class="form-group col-sm-12 col-md-12">
                                    <label for="">Noted : </label>
                                    <p>{{ empty($education->noted) ? 'N/A' : $education->noted }}</p>
                                </div>
                            </div> <!-- .row -->
                            <hr style="border: 1px solid #3c8dbc;">
                        </div> <!-- /.panel-body -->
                    @endforeach
                </div> <!-- /.panel panel-default -->
            @endif

            @if(count($staff->trainings) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading text-bold">Trainings</div>
                    @foreach($staff->trainings as $key => $training)
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Subject name : </label>
                                    <p>{{ empty($training->subject) ? 'N/A' : $training->subject }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-4">
                                    <label for="">School / Institute / University : </label>
                                    <p>{{ empty($training->school) ? 'N/A' : $training->school }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Province : </label>
                                    <p>
                                        @if($training->province_id)
                                            {{ \App\Unity::showProvince($training->province_id) }}
                                        @endif
                                        N/A
                                    </p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Start date : </label>
                                    <p>{{ empty($training->start_date) ? 'N/A' : date('d-M-Y', strtotime($training->start_date)) }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">End date : </label>
                                    <p>{{ empty($training->end_date) ? 'N/A' : date('d-M-Y', strtotime($training->end_date)) }}</p>
                                </div>
                            </div> <!-- /.row -->
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-6">
                                    <label for="">Training place : </label>
                                    <p>{{ empty($training->place) ? 'N/A' : $training->place }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-6">
                                    <label for="">Other location : </label>
                                    <p>{{ empty($training->other_location) ? 'N/A' : $training->other_location }}</p>
                                </div>
                                <div class="form-group col-sm-12 col-md-12">
                                    <label for="">Description : </label>
                                    <p>{{ empty($training->description) ? 'N/A' : $training->description }}</p>
                                </div>
                            </div> <!-- .row -->
                            <hr style="border: 1px solid #3c8dbc;">
                        </div> <!-- /.panel-body -->
                    @endforeach
                </div> <!-- /.panel panel-default -->
            @endif

            @if(count($staff->experiences) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading text-bold">Experiences</div>
                    @foreach($staff->experiences as $experience)
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Company name EN : </label>
                                    <p>{{ empty($experience->company_name_en) ? 'N/A' : $experience->company_name_en }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Company name KH : </label>
                                    <p>{{ empty($experience->company_name_kh) ? 'N/A' : $experience->company_name_kh }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Position : </label>
                                    <p>{{ empty($experience->position) ? 'N/A' : $experience->position }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Level : </label>
                                    <p>{{ empty($experience->level_position) ? 'N/A' : $experience->level_position }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Start date : </label>
                                    <p>{{ empty($experience->start_date) ? 'N/A' : date('d-M-Y', strtotime($experience->start_date)) }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">End date : </label>
                                    <p>{{ empty($experience->end_date) ? 'N/A' : date('d-M-Y', strtotime($experience->end_date)) }}</p>
                                </div>
                            </div> <!-- /.row -->
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Province / Town / City : </label>
                                    <p>{{ empty($experience->province) ? "N/A" : \App\Unity::showProvince($experience->province) }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">House number : </label>
                                    <p>{{ empty($experience->house_no) ? 'N/A' : $experience->house_no }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Street number : </label>
                                    <p>{{ empty($experience->street_no) ? 'N/A' : $experience->street_no }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-6">
                                    <label for="">Other location : </label>
                                    <p>{{ empty($experience->other_location) ? 'N/A' :$experience->other_location }}</p>
                                </div>
                            </div> <!-- /.row -->
                            <div class="row">
                                <div class="form-group col-sm-12 col-md-12">
                                    <label for="">Noted : </label>
                                    <p>{{ empty($experience->noted) ? 'N/A' : $experience->noted }}</p>
                                </div>
                            </div> <!-- .row -->
                            <hr style="border: 1px solid #3c8dbc;">
                        </div> <!-- /.panel-body -->
                    @endforeach
                </div> <!-- /.panel panel-default -->
            @endif

            @if(count($staff->spouse) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading text-bold">Spouse</div>
                    @foreach($staff->spouse as $spouse)
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Spouse full name : </label>
                                    <p>{{ empty($spouse->full_name) ? 'N/A' : $spouse->full_name }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Gender : </label>
                                    <p>
                                        @if($spouse->gender == 1)
                                            Male
                                        @elseif($spouse->gender == 0)
                                            Female
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Date of birth : </label>
                                    <p>{{ empty($spouse->dob) ? 'N/A' : date('d-M-Y', strtotime($spouse->dob)) }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Child number : </label>
                                    <p>{{ empty($spouse->children_no) ? 'N/A' : $spouse->children_no }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Child tax : </label>
                                    <p>{{ empty($spouse->children_tax) ? 'N/A' : $spouse->children_tax }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Spouse tax : </label>
                                    <p>
                                        @if($spouse->spouse_tax == 0)
                                            Exclude
                                        @elseif($spouse->spouse_tax == 1)
                                            Include
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Province : </label>
                                    <p>
                                        @if($spouse->province_id)
                                            {{ \App\Unity::showProvince($spouse->province_id) }}
                                        @endif
                                        N/A
                                    </p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">District : </label>
                                    <p>
                                        @if($spouse->district_id)
                                            {{ \App\Unity::showDistrict($spouse->district_id) }}
                                        @endif
                                        N/A
                                    </p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Commune : </label>
                                    <p>
                                        @if($spouse->commune_id)
                                            {{ \App\Unity::showCommune($spouse->commune_id) }}
                                        @endif
                                        N/A
                                    </p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Village : </label>
                                    <p>
                                        @if($spouse->village_id)
                                            {{ \App\Unity::showVillage($spouse->village_id) }}
                                        @endif
                                        N/A
                                    </p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">House number : </label>
                                    <p>{{ empty($spouse->house_no) ? 'N/A' : $spouse->house_no }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Street number : </label>
                                    <p>{{ empty($spouse->street_no) ? 'N/A' : $spouse->street_no }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Phone number : </label>
                                    <p>{{ empty($spouse->phone) ? 'N/A' : $spouse->phone }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Occupation : </label>
                                    <p>
                                        @if($spouse->occupation_id)
                                            {{ \App\Unity::showOccupation($spouse->occupation_id) }}
                                        @endif
                                        N/A
                                    </p>
                                </div>
                                <div class="form-group col-sm-12 col-md-6">
                                    <label for="">Other location : </label>
                                    <p>{{ empty($spouse->other_location) ? 'N/A' : $spouse->other_location }}</p>
                                </div>
                            </div> <!-- .row -->
                            <hr style="border: 1px solid #3c8dbc;">
                        </div> <!-- /.panel-body -->
                    @endforeach
                </div> <!-- /.panel panel-default -->
            @endif

            @if(count($staff->guarantors) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading text-bold">Guarantors</div>
                    @foreach($staff->guarantors as $key => $guarantor)
                        <div class="panel-body">
                            <div class="row">
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Guarantor name EN :</label>
                                    <p>{{ $guarantor->last_name_en." ".$guarantor->first_name_en }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Guarantor name KH :</label>
                                    <p>{{ $guarantor->last_name_kh." ".$guarantor->first_name_kh }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Gender :</label>
                                    <p>
                                        @if($guarantor->gender == 0)
                                            Male
                                        @elseif($guarantor->gender == 1)
                                            Female
                                        @else
                                            N/A
                                        @endif
                                    </p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Identify Type :</label>
                                    <p>{{ empty($guarantor->id_type) ? 'N/A' : \App\Unity::showIdType($guarantor->id_type) }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Identify Code :</label>
                                    <p>{{ empty($guarantor->id_code) ? 'N/A' : $guarantor->id_code }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Marital status :</label>
                                    <p>{{ $data_selected->guarantor[$key]->marital_status ?? 'N/A' }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Province :</label>
                                    <p>
                                        @if($guarantor->province_id)
                                            {{ \App\Unity::showProvince($guarantor->province_id) }}
                                        @endif
                                    </p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">District :</label>
                                    <p>
                                        @if($guarantor->district_id)
                                            {{ \App\Unity::showDistrict($guarantor->district_id) }}
                                        @endif
                                    </p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Commune :</label>
                                    <p>
                                        @if($guarantor->commune_id)
                                            {{ \App\Unity::showCommune($guarantor->commune_id) }}
                                        @endif
                                    </p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Village :</label>
                                    <p>
                                        @if($guarantor->village_id)
                                            {{ \App\Unity::showVillage($guarantor->village_id) }}
                                        @endif
                                    </p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">House number :</label>
                                    <p>{{ empty($guarantor->house_no) ? 'N/A' : $guarantor->house_no }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Street number :</label>
                                    <p>{{ empty($guarantor->street_no) ? 'N/A' : $guarantor->street_no }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Occupation :</label>
                                    <p>
                                        @if($guarantor->occupation_id)
                                            {{ \App\Unity::showOccupation($guarantor->occupation_id) }}
                                        @endif
                                        N/A
                                    </p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Date of birth :</label>
                                    <p>{{ empty($guarantor->dob) ? 'N/A' : date('d-M-Y', strtotime($guarantor->dob)) }}</p>
                                </div>
                                <div class="form-group col-sm-12 col-md-8">
                                    <label for="">Place of birth :</label>
                                    <p>{{ empty($guarantor->pob) ? 'N/A' : $guarantor->pob }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Mobile number :</label>
                                    <p>{{ empty($guarantor->phone) ? 'N/A' : $guarantor->phone }}</p>
                                </div>
                                <div class="form-group col-sm-6 col-md-2">
                                    <label for="">Email address :</label>
                                    <p>{{ empty($guarantor->email) ? 'N/A' : $guarantor->email }}</p>
                                </div>
                                <div class="form-group col-sm-12 col-md-8">
                                    <label for="">Other location :</label>
                                    <p>{{ empty($guarantor->other_location) ? 'N/A' : $guarantor->other_location }}</p>
                                </div>
                            </div> <!-- .row -->
                            <hr style="border: 1px solid #3c8dbc;">
                        </div> <!-- /.panel-body -->
                    @endforeach
                </div> <!-- /.panel panel-default -->
            @endif

            @if(!empty($contract))
                @php($contractObj = @to_object(@$contract->contract_object))
                @php($companyObj = @to_object(@$contractObj->company))
                @php($branchObj = @to_object(@$contractObj->branch))
                @php($departmentObj = @to_object(@$contractObj->department))
                @php($positionObj = @to_object(@$contractObj->position))
                <div class="panel panel-default">
                    <div class="panel-heading text-bold">Profile</div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Employee ID :</label>
                                <p>{{ @$contractObj->emp_id_card ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Company :</label>
                                <p>{{ @$companyObj->name_kh ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Branch :</label>
                                <p>{{ @$branchObj->name_kh ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Department :</label>
                                <p>{{ @$departmentObj->name_kh ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Position :</label>
                                <p>{{ @$positionObj->name_kh ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Manager :</label>
                                <p>{{ @$contractObj->manager ?: 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Base salary :</label>
                                <p>{{ @$contractObj->salary ?: 'N/A' }}</p>
                            </div>
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Currency :</label>
                                <p>
                                    {{ @$contractObj->currency }}
                                </p>
                            </div>
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Employment date :</label>
                                <p>{{ date('d-M-Y', strtotime(@$contract->start_date)) ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Duration of probation :</label>
                                <p>{{ @$contractObj->probation_duration ?? 'N/A' }} Months</p>
                            </div>
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Probation end date :</label>
                                <p>{{ !empty(@$contractObj->probation_end_date) ? (date('d-M-Y', strtotime(@$contractObj->probation_end_date))) : 'N/A' }}</p>
                            </div>
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Contract end date :</label>
                                <p>{{ date('d-M-Y', strtotime(@$contract->end_date)) ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Contract Type :</label>
                                @if(!empty(@$contract->contract_type))
                                <p>
                                     <span class="label label-info">
                                        {{Constants::CONTRACT_TYPE_KEY[@$contract->contract_type]}}
                                    </span>
                                </p>
                                @else
                                <p>
                                    N/A
                                </p>
                                @endif
                            </div>
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Contract duration :</label>
                                <p>{{ @$contractObj->contract_duration ?? 'N/A' }} Months</p>
                            </div>
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Manager​ phone number :</label>
                                <p>{{ @$contractObj->mobile ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group col-sm-6 col-md-2">
                                <label for="">Institutions​​​ phone number :</label>
                                <p>{{ @$contractObj->phone ?? 'N/A' }}</p>
                            </div>
                            <div class="form-group col-sm-6 col-md-4">
                                <label for="">Email :</label>
                                <p>{{ @$contractObj->email ?? 'N/A' }}</p>
                            </div>
                        </div> <!-- .row -->
                        <hr style="border: 1px solid #3c8dbc;">
                    </div> <!-- /.panel-body -->
                </div> <!-- /.panel panel-default -->
            @endif

            @if(count($staff->documents) > 0)
                <div class="panel panel-default">
                    <div class="panel-heading text-bold">Documents</div>
                    <div class="panel-body">
                        @foreach ($staff->documents->chunk(6) as $chunk)
                            <div class="row">
                                @foreach($chunk as $key => $document)
                                    <div class="form-group col-sm-6 col-md-2">
                                        <label for="">Document type :</label>
                                        <p>{{ $data_selected->document[$key]->document_name ?? 'N/A' }}</p>
                                        <label for="">Upload file :</label>
                                        <span>
                                            @if(empty($document->src))
                                                N/A
                                            @else
                                                <a href="{{ asset($document->src) }}">View file</a>
                                            @endif
                                        </span>
                                    </div>
                                @endforeach
                            </div> <!-- .row -->
                            <hr style="border: 1px solid #3c8dbc;">
                        @endforeach
                    </div> <!-- /.panel-body -->
                </div> <!-- /.panel panel-default -->
            @endif

        </div> <!-- /.col-sm-12 col-md-12 -->
    </div> <!-- /.row -->
@stop

@section('js')
    <script type="text/javascript" src="{{ asset('js/staff/index.js') }}"></script>
@stop