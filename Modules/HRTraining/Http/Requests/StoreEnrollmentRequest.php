<?php


namespace Modules\HRTraining\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class StoreEnrollmentRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'trainees' => 'required'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'trainees.required' => 'Please add staff to join this training!'
        ];
    }
}