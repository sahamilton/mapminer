<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesNotesFormRequest extends FormRequest
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
            'attachmentname' => 'required_with:attachment|min:5',
            'attachment'=>'file',
        ];
    }
}
