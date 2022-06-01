<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

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
        $salesrules = [];
        $branchrules = [];
        $oracle = [];
        $rules = [
            'roles'=>'required',
            'business_title'=>'required',
            'firstname'=>'required',
            'lastname'=>'required',
            
            'password'=>'confirmed',
            'serviceline'=>'required',
            'address'=>'required',

        ];
        if (count(array_intersect(request('roles'), [3, 5, 6, 7, 9, 17])) > 0) {
            $salesrules = ['reports_to'=>'required'];
        }
        if (count(array_intersect(request('roles'), [9])) > 0) {
            $branchrules = ['branches'=>'required_without:branchstring',
                        'branchstring'=>'required_without:branches', ];
        }
        if (request('oracle')==1) {
            $oracle =[
                'email' => 'required|email|exists:oracle,primary_email|unique:users,email,'.request()->segment(3),
                'employee_id' => 'exists:oracle,person_number|required|unique:users,employee_id,'.request()->segment(3),
            ];
        } else {
            $oracle =[
                'email' => 'required|email|unique:users,email,'.request()->segment(3),
                'employee_id' => 'required|unique:users,employee_id,'.request()->segment(3),
            ];
        }

        return array_merge($salesrules, $branchrules, $oracle, $rules);
    }

    /**
     * [messages description].
     *
     * @return [type] [description]
     */
    public function messages()
    {
        return [
            'reports_to.required' => 'Reports to is required for sales roles',
            'branches.required_without' =>'Branches or Branchstring required for branchmanagers',
            'branchstring.required_without' =>'Branches or Branchstring required for branchmanagers',
            'email.exists' =>'Email is not in Oracle data',
            'employee_id' => 'Employee ID is not in Oracle',
        ];
    }

    protected function prepareForValidation()
    {
        request()->merge(
            [
                'email' => Str::lower(request('email')),
                'firstname' => Str::ucfirst(Str::lower(request('firstname'))),
                'lastname' => Str::ucfirst(Str::lower(request('lastname'))),
            ]
        );
        
    }
}

