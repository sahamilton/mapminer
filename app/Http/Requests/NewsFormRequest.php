<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
class NewsFormRequest extends FormRequest
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
         'title' => 'required|min:5',
         'news' => 'required',
         'datefrom' => 'required|date' ,
         'dateto' => 'required|date|after:startdate',
         'serviceline'=> 'required',
         'slug'=>'required|alpha_dash|unique:news,slug,'. $this->get('id'),
        ];
    }
    /**
     * [prepareForValidation description]
     * 
     * @return [type] [description]
     */
    protected function prepareForValidation()
    {
        $this->merge(
            [
            'slug' => Str::slug($this->title),
            ]
        );
    }   
}
