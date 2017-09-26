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
        'username'=>'required|alpha_num',
         Rule::unique('users')->ignore($request->get('id')),
        'firstname'=>'required',
        'lastname'=>'required',
        'email' => 'required|email',
        Rule::unique('users')->ignore($user->id),
        'password'=>'confirmed',
        'serviceline'=>'required',

        ];
    }
}
