<?php

namespace App\Http\Requests;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CompanyFormRequest extends FormRequest
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
         'companyname' => 'required',
         'serviceline'=>'required',
         'customer_id'=> ['nullable',
            Rule::unique('companies', 'customer_id')->ignore($this->company)
            ]
        ];

    }
}
