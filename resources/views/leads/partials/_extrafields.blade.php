<legend>Additional Information</legend>
@foreach ($extrafields as $field)
<!-- field -->
    <div class="form-group{{ $errors->has($field) ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">{{ucwords(str_replace("_"," ",$field))}}</label>
<div class="input-group input-group-lg ">
                <input type="text" class="form-control" name='{{$field}}' description="{{$field}}" value="{{ old($field) ? old($field) : isset($lead->$field) ? $lead->$field : "" }}" placeholder="{{$field}}">
                <span class="help-block">
                    <strong>{{ $errors->has($field) ? $errors->first($field) : ''}}</strong>
                    </span>
            </div>
    </div>
@endforeach