<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityFormRequest extends FormRequest
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
            'activity_date'=>'date:required',
            'activitytype_id'=>'required',
            'note'=>'required',
            'followup_date'=>'date|nullable|after:activity_date',
            'followup_activity'=>'required_with:followup_date',
            'location_id' => 'required_without:address_id',
            'address_id' => 'required_without:location_id',
            'branch_id'=>'required',
        ];
    }
}
