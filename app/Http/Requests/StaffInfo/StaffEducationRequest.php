<?php

namespace App\Http\Requests\StaffInfo;

use Illuminate\Foundation\Http\FormRequest;
use Waavi\Sanitizer\Laravel\SanitizesInput;

class StaffEducationRequest extends FormRequest
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
//            'school_name'    => 'required',
//            'school_name.*'    => 'required',
//            'subject'       => 'required',
//            'subject.*'       => 'required',
//            'start_date'    => 'required',
//            'start_date.*'    => 'required',
//            'end_date.*'      => 'required',
//            'degree_id'     => 'numeric|nullable',
//            'province_id'   => 'numeric|nullable',
//            'district_id'   => 'numeric|nullable',
//            'commune_id'    => 'numeric|nullable',
//            'village_id'    => 'numeric|nullable',
//            'other_location'=> 'string|nullable',
//            'noted' => 'string|nullable',
        ];
    }

/*
    /**
     * Filters to be applied to the input.
     *
     * @return array
     */
//    public function filters()
//    {
//        return [
//            'school_name'   => 'trim|capitalize',
//            'subject'       => 'trim|capitalize',
//            'other_location' => 'trim|capitalize',
//            'noted' => 'trim|capitalize',
//        ];
//    }

}
