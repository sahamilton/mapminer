<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchReassignFormRequest extends FormRequest
{
<<<<<<< HEAD
=======
    
    

>>>>>>> master
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
<<<<<<< HEAD
        return [
            'newbranch'=>'required_without:nearbranch|exists:branches,id',
            'nearbranch'=>'required_without:newbranch'
=======
        
        return [
            'newbranch' => 'required_without:nearbranch',
            'nearbranch' => 'required_without:newbranch',
>>>>>>> master

        ];
    }
}
