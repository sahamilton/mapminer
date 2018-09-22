<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonFormRequest extends FormRequest
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
        if (!empty(array_intersect($this->attributes->get('role'), ['5','6','7','8']))) {
            return [
            'email'=>'required|email',
            'mgrtype' => 'required',
            'reportsTo'=>'required',
        
        ];

        }else{
        return [
            'email'=>'required|email',
            'mgrtype' => 'required',
        
        ];
    }
    }
}