<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationImportFormRequest extends FormRequest
{
    public $mimetypes = ['application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv', 'text/x-c', 'text/x-fortran'];

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
           'upload' => 'required|file|mimetypes:'.implode(',', $this->mimetypes),
           'description'=> 'required',

        ];
    }
}
