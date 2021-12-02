<?php


namespace Modules\PensionFund\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class StorePFSettingRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'excel_file'=>'required|mimes:xlsx, xls'
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