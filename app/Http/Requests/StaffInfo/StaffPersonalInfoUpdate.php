<?php

namespace App\Http\Requests\StaffInfo;

use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

/**
 * @property mixed personal
 */
class StaffPersonalInfoUpdate extends FormRequest
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
        $staff_id = decrypt($this->request->get('staff_token'));
        return [
            'first_name_kh' => 'required|min:1',
            'first_name_en' => 'required|min:1',
            'last_name_kh'  => 'required|min:1',
            'last_name_en'  => 'required|min:1',
            'gender'        => 'required',
//            'id_type'       => 'required',
            'id_code'   => 'required',
            'dob' => 'required',
            'pob' => 'required|min:5',
//            'bank_acc_no' => 'nullable|digits:14',
//            'phone'  => 'required|max:10|min:9|unique:staff_personal_info,phone,'.$staff_id.',id,deleted_at,NULL',
            'phone'  => 'required',
            'staffProfile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email' => 'nullable|email|unique:staff_personal_info,email,'.$staff_id.',id,deleted_at,NULL',
            'height' => 'numeric|nullable',
            'house_no' => 'numeric|nullable',
            'street_no' => 'numeric|nullable',
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
            'first_name_kh' => 'trim',
            'first_name_en' => 'trim|capitalize',
            'last_name_kh'  => 'trim',
            'last_name_en'  => 'trim|capitalize',
            'email'         => 'trim|lowercase',
            'dob'           => 'trim|format_date:d-M-Y, Y-m-d',
            'other_location'=> 'trim',
            'noted'         => 'trim',
            'emergency_contact' => 'trim',
        ];
    }
}
