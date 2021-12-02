@foreach(@$enrollments as $key => $value)
    @php
        $isNotEmpty = @$value->traineesRequested && count(@$value->traineesRequested);
    @endphp
    <div class="modal fade in modal-approve" id="form-modal-{{ $value->id }}" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                    <h4 class="modal-title">Approve staff requested join on training list</h4>
                </div>

                <form action="{{ route('hrtraining::enrollment.approve') }}" method="post" id="form-approve"
                      class="form-approve">
                    {{ csrf_field() }}

                    <input type="hidden" id="enrollment_id" name="enrollment_id" class="enrollment_id"
                           value="{{ @$value->id }}"/>
                    <input type="hidden" id="approve_type" name="approve_type" class="approve_type"/>

                    <div class="modal-body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="check_all" class="check_all" id="check_all"/>
                                </th>
                                <th>Staff ID</th>
                                <th>Full Name</th>
                                <th>Gender</th>
                                <th>Position</th>
                                <th>Company</th>
                                <th>Requested Date</th>
                                <th>
                                    Status
                                </th>
                            </tr>
                            </thead>
                            <tbody>

                            @if($isNotEmpty)
                                @foreach(@$value->traineesRequested as $key => $value)
                                    @php
                                        $staffInfo = $value->staff;
                                        $companyObj = @$value->company;
                                        $positionObj = @$value->position;
                                        $jsonObj = @to_object(@$value->json_data);
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="checkbox" name="check[]" id="checkbox"
                                                   value="{{ @$value->id }}"/>
                                        </td>
                                        <td>
                                            {{ $value->staff_personal_id }}
                                        </td>
                                        <td>
                                            {{ @$staffInfo->last_name_kh . ' ' . @$staffInfo->first_name_kh }}
                                        </td>
                                        <td>
                                            {{ GENDER[@$staffInfo->gender] ?? 'N/A' }}
                                        </td>
                                        <td>
                                            {{ @$positionObj->name_kh }}
                                        </td>
                                        <td>
                                            {{ @$companyObj->name_kh }}
                                        </td>
                                        <td>
                                            {{ date('d-M-Y', strtotime(@$value->created_at)) }}
                                        </td>
                                        <td>
                                              <span class="badge bg-blue">
                                                  {{ getTraineeRequestJoinStatusKey($value->request_join_status) }}
                                              </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">
                                        <label>Empty!</label>
                                    </td>
                                </tr>
                            @endif

                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        @if($isNotEmpty)
                            <button id="btn-reject" type="button" class="btn btn-danger btn-reject"
                                    data-dismiss="modal">Reject
                            </button>
                            <button id="btn-approve" type="button" class="btn btn-primary btn-approve"
                                    data-dismiss="modal">
                                Approve
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach