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
        
       
        return [
        'username'=>'required|alpha_num|unique:users,id,' . $request->segment(3),
        'firstname'=>'required',
        'lastname'=>'required',
        'email' => 'required|email|unique:users,id,' . $request->segment(3),
        'password'=>'confirmed',
        'serviceline'=>'required',
        'employee_id'=>'required|unique:users,id,' . $request->segment(3),

        ];
    }
}