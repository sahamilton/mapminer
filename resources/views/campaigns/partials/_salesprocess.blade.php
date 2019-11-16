@foreach ($process as $key=>$value)

	@if((isset($activity->salesprocess) && $activity->salesprocess->contains('step',$value))
	or is_array(old('salesprocess')) && in_array($key,old('salesprocess')))
	<p><input type="checkbox" name="salesprocess[]" checked value="{{$key}}">{{$value}} </p>
	@else
	<p><input type="checkbox" name="salesprocess[]"  value="{{$key}}">{{$value}} </p>
	@endif

@endforeach