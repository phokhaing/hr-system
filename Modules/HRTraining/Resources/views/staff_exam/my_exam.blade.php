@extends('adminlte::page')

@section('title', 'Result Examination')

@section('content')

    @include('partials.breadcrumb')

    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-newspaper-o"></i> <b>Result Examination</b>
                </div>
                <div class="panel-body">
                    <div style="overflow-x: auto">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Action</th>
                                <th>Course Title</th>
                                <th>Total Score</th>
                                <th>Average</th>
                                <th>Training Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(is_object($trainees))
                                @foreach($trainees as $key => $trainee)
                                <tr>
                                    <td>
                                        <a target="_blank" href="{{ route('hrtraining::examination.detailResult', (encrypt($trainee->traineeResult->id))) }}"
                                           class="btn btn-xs btn-default" title="View Detail">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                    <td>{{ $trainee->enrollment->course->json_data->title }}</td>
                                    <td><label class="badge bg-red">{{ number_format($trainee->traineeResult->json_data->total_point, 2) }} </label></td>
                                    <td>
                                        <label class="badge bg-red">{{ number_format($trainee->traineeResult->json_data->average, 2) }}</label>
                                    </td>
                                    <td>{{ date_readable($trainee->enrollment->json_data->start_date) }}
                                         <i class="fa fa-long-arrow-right"></i>
                                        {{ date_readable($trainee->enrollment->json_data->end_date) }} </td>
                                </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

