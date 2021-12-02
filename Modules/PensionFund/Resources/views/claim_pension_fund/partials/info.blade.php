<div class="panel panel-default">
    <div class="panel-heading"><label for=""><i class="fa fa-address-card-o"></i> INFO </label></div>
    <div class="panel-body">

        <div class="row">
            <div class="form-group col-sm-6 col-md-3 @if($errors->has('last_name_kh')) has-error @endif">
                <label for="last_name_kh">Last Name KH <span class="text-danger">*</span></label>
                <input type="text" id="last_name_kh" name="last_name_kh" class="form-control" readonly
                       placeholder="{{ __('label.last_name_kh') }}" value="{{ old('last_name_kh') }}">
            </div>
            <div class="form-group col-sm-6 col-md-3 @if($errors->has('first_name_kh')) has-error @endif">
                <label for="first_name_kh">First Name KH <span class="text-danger">*</span></label>
                <input type="text" id="first_name_kh" name="first_name_kh" class="form-control" readonly
                       placeholder="{{ __('label.first_name_kh') }}" value="{{ old('first_name_kh') }}">
            </div>
            <div class="form-group col-sm-6 col-md-3 @if($errors->has('last_name_en')) has-error @endif">
                <label for="last_name_en">Last Name EN <span class="text-danger">*</span></label>
                <input type="text" id="last_name_en" name="last_name_en" class="form-control" readonly
                       placeholder="{{ __('label.last_name_en') }}" value="{{ old('last_name_en') }}">
            </div>
            <div class="form-group col-sm-6 col-md-3 @if($errors->has('first_name_en')) has-error @endif">
                <label for="first_name_en">First Name EN <span class="text-danger">*</span></label>
                <input type="text" id="first_name_en" name="first_name_en" class="form-control" readonly
                       placeholder="{{ __('label.first_name_en') }}" value="{{ old('first_name_en') }}">
            </div>
        </div> <!-- /.row -->

        <div class="row">
            <div class="form-group col-sm-6 col-md-3 @if($errors->has('marital_status')) has-error @endif">
                <label for="marital_status">Marital Status <span class="text-danger">*</span></label>
                <input type="text" id="marital_status" name="marital_status" placeholder="{{ __('ស្ថានភាព') }}" 
                       class="form-control" value="{{ old('marital_status') }}" readonly>
            </div>
            <div class="form-group col-sm-6 col-md-3 @if($errors->has('gender')) has-error @endif">
                <label for="gender">Gender <span class="text-danger">*</span></label>
                <input type="text" id="gender" name="gender" placeholder="{{ __('ភេទ') }}" 
                       class="form-control" value="{{ old('gender') }}" readonly>
            </div>
            
            <div class="form-group col-sm-6 col-md-3 @if($errors->has('position')) has-error @endif">
                <label for="position">Position <span class="text-danger">*</span></label>
                <input type="text" id="position" name="position" placeholder="{{ __('តួនាទី') }}"
                       class="form-control" value="{{ old('position') }}" readonly>
            </div>
            
            <div class="form-group col-sm-6 col-md-3 @if($errors->has('doe')) has-error @endif">
                <label for="doe">Start Date <span class="text-danger">*</span></label>
                <input type="text" id="doe" name="doe" placeholder="{{ __('ថ្ងៃចូលធ្វើការ') }}"
                       class="form-control" value="{{ old('doe') }}" readonly>
            </div>
        </div> <!-- /.row -->
    </div>
</div>