<div class="form-group{{ $errors->has('vertical') ? ' has-error' : '' }}">
<label for="vertical">Vertical</label>
<div class="input-group input-group-lg ">


@foreach ($verticals as $key=>$value)
	@if((isset($lead->vertical) && $lead->vertical->contains('filter',$value))
	or is_array(old('vertical')) && in_array($key,old('vertical')))
	<input  type="checkbox" checked name="vertical[]" value="{{$key}}" />{{$value}}<br />
	@else
	<input type="checkbox" name="vertical[]" value="{{$key}}" />{{$value}}<br />
	@endif

@endforeach

<strong>{!! $errors->first('vertical', '<p class="help-block">:message</p>') !!}</strong>
</div></div>
