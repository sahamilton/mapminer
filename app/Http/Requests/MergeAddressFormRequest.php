<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MergeAddressFormRequest extends FormRequest
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
            'original'=>'required',
            'address'=>'array|required',
        ];
    }
    /**
     * [messages description]
     * 
     * @return [type] [description]
     */
    public function messages()
    {
        return [
            'original.required' => 'You must specify an address to merge duplicates into',
            'address.required' => 'You must specify at least one address to merge',
       
        ];
    }
}
