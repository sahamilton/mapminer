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
    // we need to validate that you cannot reassign to the same branch
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $valid = $this->_getValid();
       
        return [
            'branch_id' => 'required_without:branch',
            'branch' => 'required_without:branch_id',
            'current_id'=>'notIn:[branch_id, branch]'

        ];
    }
    
    private function _getValid()
    {
        $valid = [];
        
        if (isset($this->request->branch)) {
            $valid[] = $this->request->branch;
            dd(46, $this->request->branch);
        }
        if (isset($this->request->branch_id)) {
            $valid[] = $this->request->branch_id;
            dd(50, $this->request->branch_id);
        }
        return $valid;
    }
}
