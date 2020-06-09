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
        $type = $this->request->get('type'); 
        $rules = [
            'title'=>'required',
            'description'=>'required',
            'datefrom'=>'required|date|before:dateto',
            'dateto'=>'required|date|after:datefrom',
            'type'=>'required',
            'serviceline'=>'required'
        ];
        if ($type != 'open') {
            
                $rules['companies'] = 'required_without:vertical';
                $rules['vertical'] = 'required_without:companies';
            
        }
        return $rules;
        
    }
    public function messages()
    {
        return [
            'companies.required_without' => 'You must specify either companies or vertical for restricted campaigns',
            'vertical.required_without'  => 'You must specify either companies or vertical for restricted campaigns',
        ];
    }

}
