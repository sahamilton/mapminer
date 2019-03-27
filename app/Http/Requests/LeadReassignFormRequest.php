<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadReassignFormRequest extends FormRequest
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
            'branch_id' => 'required_without:branch|regex:/^[0-9,]*$/|exists:branches,id',
            'branch' => 'required_without:branch_id',

        ];
    }
        public function messages()
        {
            return [
                'branch_id.regex' => 'Use only numerics and commas for branch id',
                'branch_id.exists' => 'Invalid branch id',
                
            ];
        }

}
