<div class="form-group{{ $errors->has('vertical') ? ' has-error' : '' }}">
<label for="salesprocess">Sales Process Step</label>
<div class="input-group input-group-lg ">

@foreach ($process as $key=>$value)

	@if(isset($activity->salesprocess) && $activity->salesprocess->contains('step',$value))
	<input type="checkbox" name="salesprocess[]" checked value="{{$key}}">{{$value}}
	@else
	<input type="checkbox" name="salesprocess[]"  value="{{$key}}">{{$value}}
	@endif

@endforeach

{!! $errors->first('salesprocess', '<p class="help-block has-error">:message</p>') !!}
</div></div>