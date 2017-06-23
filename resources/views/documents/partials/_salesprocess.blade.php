<div class="form-group{{ $errors->has('salesprocess') ? ' has-error' : '' }}">
<label for="salesprocess">Sales Process Step</label>
<div class="input-group input-group-lg ">

@foreach ($process as $key=>$value)

	@if((isset($document->process) && $document->process->contains('step',$value))
	or is_array(old('salesprocess')) && in_array($key,old('salesprocess')))
	<input type="checkbox" name="salesprocess[]" checked value="{{$key}}">{{$value}}
	@else
	<input type="checkbox" name="salesprocess[]"  value="{{$key}}">{{$value}}
	@endif

@endforeach

<strong>{!! $errors->first('salesprocess', '<p class="help-block">:message</p>') !!}</strong>
</div></div>