<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpportunityFormRequest extends FormRequest
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
            'expected_close'=>'required_if:closed,0|date|nullable|after_or_equal:today',
            'actual_close'=>'required_if:closed,1,2|date|nullable|before_or_equal:today',
           
        ];
    }
}
