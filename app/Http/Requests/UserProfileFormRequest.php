<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileFormRequest extends FormRequest
{
    
    public function __construct()
    {
    }

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
      /*
        The password contains characters from at least three of the following five categories:
            English uppercase characters (A – Z)
            English lowercase characters (a – z)
            Base 10 digits (0 – 9)
            Non-alphanumeric (For example: !, $, #, or %)
            Unicode characters
      */
        return [
        
        'firstname'=>'required',
        'lastname'=>'required',
        'password'=>['nullable','sometimes',
               'min:8',
               'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\X])(?=.*[!$#%]).*$/',
               'confirmed'],
    

        ];
    }
        /***
        * Get the error messages for the defined validation rules.
        *
        * @return array
        */
    public function messages()
    {
        return [
        
        'password.regex' => 'The password must contains characters from at least three of the following five categories:
                English uppercase characters (A – Z)
                English lowercase characters (a – z)
                Base 10 digits (0 – 9)
                Non-alphanumeric (For example: !, $, #, or %)
                Unicode characters',
       
        ];
    }
}
