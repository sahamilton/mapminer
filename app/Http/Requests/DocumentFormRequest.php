<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory;

class DocumentFormRequest extends FormRequest
{
    private $mimetypes = ['application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/pdf'];
    public function __construct(Factory $factory)
    {
        $factory->extend(
            'empty_with',
            function ($attribute, $value, $parameters) {
                return ($value != '' && $parameters[0] != '') ? false : true;
            },
            'Enter either file or link but not both'
        );
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
            'title'=>'required',
            'summary'=>'required',
            'description'=>'required',
            'datefrom'=>'required|date',
            'dateto'=>'required',
            'file'=>'required_without_all:location|file|mimetypes:'.implode(",", $this->mimetypes),
            'location'=>'required_without_all:file',
            'vertical'=>'required',
            'salesprocess'=>'required',
        ];
    }

    public function messages()
    {
        return [
        'file.required_without_all'=>'You need to upload a file or enter a valid url link but not both',
        'file.file'=>'You need to specify a file',
        'location.required_without_all'=>'You need to enter a valid url link or upload a file but not both',
        'location.url'=>'Please enter a valid URL e.g. http://mydomain.com',
        'file.mimetypes'=>"Only PDF or Word (.doc or .docx) files permitted",
        ];
    }
}
