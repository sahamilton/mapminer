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
        
        $rules['closed'] = 'required';
        $rules['expected_close'] = 'date';
        $rules['value'] = 'numeric|min:1';    
        if (request('closed') == 0) {

            $rules['expected_close'] = 'required|date|after_or_equal:today';
        } else {
            if (! request()->filled('actual_close') && request()->filled('expected_close')) {
                request()->merge(['actual_close'=>request('expected_close')]);

            } 
            $rules['actual_close'] = 'required_without:expected_close|date|before_or_equal:today';
            

        }
        
        return $rules;
    }

    /**
     * [messages description]
     * 
     * @return [type] [description]
     */
    public function messages()
    {
        return [
        'expected_close.required' => 'expected close / actual close is required',
        
       
        ];
    }

    protected function prepareForValidation()
    {
      

        if (! $this->closed == 0) {
            $this->actual_close = $this->expected_close;
        }
    }
}
