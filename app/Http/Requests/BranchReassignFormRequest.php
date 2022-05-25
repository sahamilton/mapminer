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
            'dontreassign'=>'required_without:newbranch,newbranch',
            'newbranch'=>'required_without:dontreassign',
            'nearbranch'=>'required_without:dontreassign'


        ];
    }
}
