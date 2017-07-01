@foreach ($process as $key=>$value)

	@if((isset($document->process) && $document->process->contains('step',$value))
	or is_array(old('salesprocess')) && in_array($key,old('salesprocess')))
	<input type="checkbox" name="salesprocess[]" checked value="{{$key}}">{{$value}}
	@else
	<input type="checkbox" name="salesprocess[]"  value="{{$key}}">{{$value}}
	@endif

@endforeach

