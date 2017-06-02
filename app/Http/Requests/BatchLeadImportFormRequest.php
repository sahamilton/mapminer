<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchLeadImportFormRequest extends FormRequest
{
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
            'vertical'=>'required',
            'file'=>'file|required',
            'datefrom'=>'required|before_or_equal:dateto',
            'dateto'=>'required',

        ];
    }
}
