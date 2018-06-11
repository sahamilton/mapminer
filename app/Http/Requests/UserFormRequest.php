<?php

namespace App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserFormRequest extends FormRequest
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
    public function rules(Request $request)
    {
        $salesrules=array();

       if (count(array_intersect($request->get('roles'), [5,6,7,8]))>0){
            $salesrules = ['reports_to'=>'required'];
        }
       if (count(array_intersect($request->get('roles'), [9]))>0){
            
            $branchrules = ['branches'=>'required_without:branchstring',
                        'branchstring'=>'required_without:branches',];
        }
        $rules = [
            'username'=>'required|alpha_num|unique:users,username,' . $request->segment(3),
            'firstname'=>'required',
            'lastname'=>'required',
            'email' => 'required|email|unique:users,email,' . $request->segment(3),
            'employee_id' => 'required|unique:users,employee_id,' . $request->segment(3),
            'password'=>'confirmed',
            'serviceline'=>'required',

        ];
        return array_merge($salesrules,$branchrules,$rules) ;
    }
    public function messages()
    {
        return [
            'reports_to.required' => 'Reports to is required for sales roles',
            'branches.required_without' =>'Branches or Branchstring required for branchmanagers',
            'branchstring.required_without' =>'Branches or Branchstring required for branchmanagers',
        ];
    }

}