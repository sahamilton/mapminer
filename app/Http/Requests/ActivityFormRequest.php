<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Factory;
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
            'activity_date'=>'date|required',
            'activitytype_id'=>'required',
            'note'=>'required',
            'followup_date'=>'date|nullable|after:activity_date',
            'followup_activity'=>'required_with:followup_date',
            'location_id' => 'required_without:address_id',
            'address_id' => 'required_without:location_id',
            'branch_id'=>'required',
        ];
    }
    /**
     * [messages description]
     * 
     * @return [type] [description]
     */
    public function messages()
    {
        return ['activity_date.before_or_equal:today'=>'The activity date for a completed activity must be a date before or equal to today.'];
    }

    public function validator(Factory $factory)
    {
        $validator = $factory->make($this->input(), $this->rules(), $this->messages(), $this->attributes());

        $validator->sometimes(
            'activity_date', 'before_or_equal:today', function ($input) {
                return $input->completed === '1' ;
            }
        );

        return $validator;
    }
}
