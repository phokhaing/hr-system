@extends('staffs.edit')

@section('tab')
    <div class="tab-pane active">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="box">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover table-striped">
                            <tbody>
                            <tr>
                                <th>Course ID</th>
                                <th>Course Title</th>
                                <th>Category</th>
                                <th>Total Training</th>
                            </tr>

                            @foreach($courses as $key => $course)
                                <tr>
                                    <td>{{ $course->course_id }}</td>
                                    <td>{{ @$course->course_title ?: 'N/A' }}</td>
                                    <td>{{ @$course->category_title ?: 'N/A' }}</td>
                                    <td>
                                        <span class="label label-danger">
                                            {{ @$course->total_training }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection