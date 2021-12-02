<?php


namespace Modules\HRTraining\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class UpdateCategorySettingRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title_en' => 'required|min:2',
            'title_kh' => 'required|min:2',
            'name_en' => 'nullable',
            'name_kh' => 'nullable'
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