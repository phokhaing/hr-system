<?php


namespace Modules\HRTraining\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class StoreCourseContentSoundSettingRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'section_audio' => 'required|file|size:10000'
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