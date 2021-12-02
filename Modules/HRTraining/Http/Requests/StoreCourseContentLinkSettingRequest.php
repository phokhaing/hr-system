<?php


namespace Modules\HRTraining\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class StoreCourseContentLinkSettingRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'section_title' => 'required',
            'section_link' => 'required|url',
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
}