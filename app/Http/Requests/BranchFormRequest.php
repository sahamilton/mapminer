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
        return ['branchname'=>'required',
        'branchnumber'=>'required|unique:branches,branchnumber,'. $this->request->get('branchnumber'),
        'street'=>'required',
        'city'=>'required',
        'state'=>'required',
        'zip'=>'required',
        'region_id'=>'required',
        'radius'=>'required',
            //
        ];
    }
}
