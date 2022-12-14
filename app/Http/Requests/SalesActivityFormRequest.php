<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesActivityFormRequest extends FormRequest
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
            'title'=>'required',
            'datefrom'=>'required|date|date_format:m/d/Y',
            'dateto'=>'required|date|date_format:m/d/Y|after:datefrom',
            'description'=>'required',
            'companies'=>'required',
            'salesprocess'=>'required',
        ];
    }
}
