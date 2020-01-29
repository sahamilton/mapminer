<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsFormRequest extends FormRequest
{
    public function __construct()
    {
        \Input::merge(['slug' => strtolower(str_replace(' ', '_', \Input::get('title')))]);
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
        return [
         'title' => 'required|min:5',
         'news' => 'required',
         'datefrom' => 'required|date',
         'dateto' => 'required|date|after:startdate',
         'serviceline'=> 'required',
         'slug'=>'required|alpha_dash|unique:news,slug,'.$this->get('id'),
        ];
    }
}
