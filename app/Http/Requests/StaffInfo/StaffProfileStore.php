<?php

namespace App\Http\Requests\StaffInfo;

use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class StaffProfileStore extends FormRequest
{
    use SanitizesInput;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'profile.emp_id_card'   => 'required',
            'profile.branch_id'     => 'required',
            'profile.company_id'    => 'required',
            'profile.dpt_id'        => 'required',
            'profile.position_id'   => 'required',
            'profile.base_salary'   => 'required',
            'profile.currency'      => 'required',
            'profile.employment_date'=> 'required',
            'profile.probation_end_date'    => 'required',
            'profile.probation_duration'    => 'required|numeric',
            'profile.contract_end_date'     => 'required',
            'profile.contract_duration'     => 'required|numeric',
            'profile.manager'   => 'required',
//            'profile.home_visit'=> 'required',
            'profile.email'     => 'nullable|email',
            'profile.phone'     => 'nullable|max:10',
            'profile.mobile'    => 'nullable|min:9|max:10',
        ];
    }

    /**
     * Customize message
     *
     * @return array
     */
    public function messages()
    {
        return [
            'profile.emp_id_card.required'   => 'Employee ID is required',
            'profile.branch_id.required'     => 'Branch is required',
            'profile.company_id.required'    => 'Company is required',
            'profile.dpt_id.required'        => 'Department is required',
            'profile.position_id.required'   => 'Position is required',
            'profile.base_salary.required'   => 'Base salary is required',
            'profile.currency.required'      => 'Currency is required',
            'profile.employment_date.required'=> 'Employment date is required',
            'profile.probation_end_date.required'    => 'Probation end date is required',
            'profile.probation_duration.required'    => 'Probation duration is required',
            'profile.probation_duration.numeric'    => 'Probation duration must numeric',
            'profile.contract_end_date.required'     => 'Contract end date is required',
            'profile.contract_duration.required'     => 'Contract duration is required',
            'profile.contract_duration.numeric'     => 'Contract duration must number',
            'profile.manager.required'   => 'Manager is required',
            'profile.email.email'     => 'Email invalid',
            'profile.phone.max'     => 'Phone length max 10 character',
            'profile.mobile.min'    => 'Phone length min 9 character',
            'profile.mobile.max'    => 'Phone length max 10 character',
        ];
    }

    /**
     * Filters to be applied to the input.
     *
     * @return array
     */
    public function filters()
    {
        return [
            'profile.email'     => 'trim|lowercase',
            'profile.employment_date'   => 'trim|format_date:d-M-Y, Y-m-d',
            'profile.probation_end_date' => 'trim|format_date:d-M-Y, Y-m-d',
            'profile.contract_end_date'  => 'trim|format_date:d-M-Y, Y-m-d',
        ];
    }


}
