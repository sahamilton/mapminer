<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationFormRequest extends FormRequest
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
            'businessname' => 'required',
        'street' => 'required',
        'city' => 'required',
        'state' => 'required|exists:states,statecode',
        'zip' => 'required',
        'company_id' => 'required',
        'businesstype' => 'required'
        ];
    }
}
