<legend>Industry Verticals</legend>
<div class="form-group{{ $errors->has('vertical') ? ' has-error' : '' }}">
	<label class="col-md-4 control-label">Vertical</label>
	<div class="col-md-6 input-group input-group-lg ">


		@foreach ($verticals as $key=>$value)
			@if((isset($leadsource->verticals) && $leadsource->verticals->contains('filter',$value))
			or is_array(old('vertical')) && in_array($key,old('vertical')))
				<input  type="checkbox" checked name="vertical[]" value="{{$key}}" />{{$value}}<br />
			@else
				<input type="checkbox" name="vertical[]" value="{{$key}}" />{{$value}}<br />
			@endif

		@endforeach

		<strong>{!! $errors->first('vertical', '<p class="help-block">:message</p>') !!}</strong>
	</div>
</div>

