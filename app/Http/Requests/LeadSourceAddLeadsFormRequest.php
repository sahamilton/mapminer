<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadSourceAddLeadsFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public $mimetypes = ['application/vnd.ms-excel', 'text/plain', 'text/csv', 'text/tsv', 'text/x-c'];

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
        return ['companyname'=>'required_without:file',
                'businessname'=>'required_without:file',
                'address'=>'required_without:file',
                'city'=>'required_without:file',
                'state'=>'required_without:file|exists:state,statecode',
                'zip'=>'required_without:file',
                'phone'=>'required_without:file',
                'contact'=>'required_without:file',

                'file'=>'file|required_without:companyname,businessname,address,city,state,zip,phone|mimetypes:'.implode(',', $this->mimetypes),
        ];
    }
}
