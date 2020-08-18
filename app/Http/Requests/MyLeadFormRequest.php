<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MyLeadFormRequest extends FormRequest
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
            'address'=>'required:street',
            'campaign' => 'sometimes|required|array',
            'campaign.*' => 'sometimes|required|numeric',
            'companyname'=>'required|filled',
            'phone'=>'sometimes|nullable|numeric',
            'email'=>'sometimes|nullable|email',
        ];
    }
    protected function prepareForValidation()
    {
        
        if ($this->has('phone')) {
            $this->merge(['phone'=>preg_replace("/[^0-9]/", "", $this->phone)]);
        }
    }
}
