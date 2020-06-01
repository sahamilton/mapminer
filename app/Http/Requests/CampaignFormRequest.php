<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignFormRequest extends FormRequest
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
            'title'=>'required',
            'description'=>'required',
            'datefrom'=>'required|date|before:dateto',
            'dateto'=>'required|date|after:datefrom',
            /*'companies'=>'required_without:vertical',
            'vertical'=>'required_without:companies',*/
           
            'serviceline'=>'required',
        ];
    }
}
