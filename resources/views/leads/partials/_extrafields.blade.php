<legend>Additional Information</legend>
@if(isset($lead->webled) && $lead->weblead->isEmpty()))
down
@else
up
@endif
@foreach ($extrafields as $field)
<!-- field -->
    <div class="form-group{{ $errors->has($field) ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">{{ucwords(str_replace("_"," ",$field))}}</label>
		<div class="input-group input-group-lg ">
            <input 
            type="text" 
            class="form-control" 
            name='{{$field}}' 
            description="{{$field}}" 
            value="{{ old($field, isset($lead->weblead) && ! $lead->weblead->isEmpty() ?  $lead->weblead->$field : '' )}}" 
            placeholder="{{$field}}">
            <span class="help-block">
                <strong>{{ $errors->has($field) ? $errors->first($field) : ''}}</strong>
                </span>
        </div>
    </div>
@endforeach