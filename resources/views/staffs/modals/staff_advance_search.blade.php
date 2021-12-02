<!-- Default Size -->
<div class="modal fade" id="advanced_search" tabindex="-1" role="dialog">
    <form action="{{ route('staff.filter') }}" role="form" method="get" id="search-form"
          class="body">
        <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">

                <div class="modal-header">
                    <h6 class="title">
                        <i class="fas fa-search-plus"></i> Advanced Search
                    </h6>
                </div>
                <div id="modalWrapper" class="modal-body">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-3">
                            <input type="text" name="key_word" id="key_word" class="form-control" placeholder="Keyword"
                                   value="{{ request('key_word') }}">
                        </div>
                        <div class="form-group col-sm-6 col-md-3">
                            <select name="company" id="company" class="form-control js-select2-single">
                                <option value=""> << {{ __("label.company") }} >></option>
                                @foreach($companies as $key => $company)
                                @if($company->id == request('company'))
                                <option value="{{ $company->id }}" selected>{{ $company->short_name }}</option>
                                @else
                                <option value="{{ $company->id }}">{{ $company->short_name }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-3">
                            <select name="branch" id="branch" class="form-control js-select2-single">
                                <option value=""> << {{ __("label.branch") }} >></option>
                                @foreach($branches as $key => $branch)
                                @if($branch->id == request('branch'))
                                <option value="{{ $branch->id }}" selected>{{ $branch->name_kh }}</option>
                                @else
                                <option value="{{ $branch->id }}">{{ $branch->name_kh }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-3">
                            <select name="department" id="department" class="form-control js-select2-single">
                                <option value=""> << {{__('label.department') }} >></option>
                                @foreach($departments as $key => $department)
                                @if($department->id == request('department'))
                                <option value="{{ $department->id }}" selected>{{ $department->name_kh }}</option>
                                @else
                                <option value="{{ $department->id }}">{{ $department->name_kh }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-sm-6 col-md-3">
                            <select name="position" id="position" class="form-control js-select2-single">
                                <option value=""> << {{ __('label.position') }} >></option>
                                @foreach($positions as $key => $position)
                                @if($position->id == request('position'))
                                <option value="{{ $position->id }}" selected>{{ $position->name_kh }}</option>
                                @else
                                <option value="{{ $position->id }}">{{ $position->name_kh }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-3">
                            <select name="contract_type" id="contract_type" class="form-control js-select2-single">
                                <option value=""> << {{ __('label.contract_type') }} >></option>
                                @foreach(Constants::CONTRACT_TYPE_KEY as $key => $value)
                                <option value="{{ $key }}" {{request(
                                'contract_type') == $key ? 'selected' : ''}}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-6 col-md-3">
                            <button type="submit" class="btn btn-default margin-r-5"><i class="fa fa-search"></i> Search
                            </button>
                            <button type="button" id="btn-reset" class="btn btn-danger margin-r-5"><i
                                        class="fa fa-remove"></i> Clear
                            </button>
                        </div>
                    </div>

                </div>

                <div class="row col-lg-12 col-md-12 col-sm-12 m-b-10 d-flex justify-content-end">
                    <button type="button" class="btn btn-neutral" data-dismiss="modal">
                        {{ trans('hmis.close') }}
                    </button>

                    <button type="submit" id="filter" class="btn btn bg-blue waves-effect waves-light">
                        {{ trans('hmis.search') }}
                    </button>
                </div>

            </div>

        </div>
    </form>
</div>