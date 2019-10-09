<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchManagementRequest extends FormRequest
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
            'branch.*' => 'exists:branches,id', // check each item in the array
        ];
    }
    /**
     * [messages description]
     * 
     * @return [type] [description]
     */
    public function messages()
    {
        return [
        'branch.*.exists' => 'One or more of the branch ids is invalid',
       
        ];
    }
    /**
     * [getValidatorInstance description]
     * 
     * @return [type] [description]
     */
    protected function getValidatorInstance()
    {
        $data = $this->all();
        $data['branch'] = array_filter(array_unique(explode(",", $data['branches'])));
        $this->getInputSource()->replace($data);

        /*modify data before send to validator*/

        return parent::getValidatorInstance();
    }
}
