@extends('adminlte::page')

@section('title', 'Upload Pension Fund')

@section('content')

@include('partials.breadcrumb')

<div class="panel panel-default">
    <div class="panel-heading"><label for="">Upload Pension Fund</label></div>
    <div class="panel-body">
        {{ session()->get('message') }}
        <div class="row">
            <div class="col-sm-12">

                <form action="{{ route('pensionfund::pf.import') }}" enctype="multipart/form-data" method="POST" role="form">
                    {{ Form::token() }}
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-6 @if($errors->has('excel_file')) has-error @endif">
                            <?php
                                form_label([
                                    'for' => 'excel_file',
                                    'value' => 'File Excel *',
                                ]);
                                form_file([
                                    'name' => 'excel_file',
                                    'class' => 'form-control',
                                    'id' => 'excel_file', 
                                    'value' => old('excel_file')
                                ]);
                            ?>
                        </div>
                        
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-upload"></i>
                                Upload
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div> <!-- .panel-default -->


<div class="panel panel-info">
        <div class="panel-heading"><label for="">Preview pension fund info</label></div>
        <div class="panel-body table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Report Date</th>
                        <th>System Code</th>
                        <th>Name In English</th>
                        <th>Gender</th>
                        <th>Location</th>
                        <th>Location Short Name</th>
                        <th>D.O.E</th>
                        <th>Effective Date</th>
                        <th>Base Salary</th>
                        <th>Gross Base Salary</th>
                        <th>Addition</th>
                        <th>Deduction</th>
                        <th>Acr By Staff</th>
                        <th>Acr By Company</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody id = "show_data">
                    <tr>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                        <td>N/A</td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div> <!-- .panel-default -->

@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#excel_file').change(function(){
                var fd = new FormData();
                var files = $('#excel_file')[0].files;
                
                // Check file selected or not
                if(files.length > 0 ){
                    fd.append('file',files[0]);

                    $.ajax({
                        url: "{{ route('pensionfund::pf.verify') }}",
                        type: 'post',
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function(response){
                            console.log('my response: ' + response);
                            
                            $('#show_data').empty();
                            let arr = [];
                            let i = 1;
                            $.each( response, function( key, value ) {
                                $('#show_data').append(  '<tr>' );
                                $('#show_data').append( '<td>' + i + '</td>' );
                                $.each( value, function( ky, val ) {
                                    $('#show_data').append( '<td>' + val + '</td>' );
                                });    
                                $('#show_data').append(  '</tr>' );
                                i++;
                            });

                        },
                        error: function(data) {
                            console.log('error');
                        }
                    });
                }else{
                    alert("Please select a file.");
                }
            });
            return false;
        });
    </script>
@endsection
