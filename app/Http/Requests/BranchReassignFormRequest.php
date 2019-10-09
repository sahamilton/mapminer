<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchReassignFormRequest extends FormRequest
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
            'newbranch'=>'required_without:nearbranch|exists:branches,id',
            'nearbranch'=>'required_without:newbranch'


        ];
    }
}
