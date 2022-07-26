<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchFormRequest extends FormRequest
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
        if ($this->method() == 'POST') {
            return [
                'branchname'=>'required',
                'id'=>'required|unique:branches,id',
                'street'=>'required',
                'city'=>'required',
                'state'=>'required|exists:states,statecode',
                'zip'=>'required',
                
                'radius'=>'required',
                'serviceline'=>'required',
                
            ];
        } else {
            return [
                'branchname'=>'required',
                'id'=>'required|unique:branches,id,'.$this->id,
                'street'=>'required',
                'city'=>'required',
                'state'=>'required|exists:states,statecode',
                'zip'=>'required',
                
                'radius'=>'required',
                'serviceline'=>'required',
            ];
        }
    }
}
