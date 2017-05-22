<div class="form-group{{ $errors->has('vertical') ? ' has-error' : '' }}">
<label for="vertical">Vertical</label>
<div class="input-group input-group-lg ">


@foreach ($verticals as $key=>$value)
	@if(isset($document->vertical) && $document->vertical->contains('filter',$value))
	<input  type="checkbox" checked name="vertical[]" value="{{$key}}" />{{$value}}<br />
	@else
	<input type="checkbox" name="vertical[]" value="{{$key}}" />{{$value}}<br />
	@endif

@endforeach

{!! $errors->first('vertical', '<p class="help-block">:message</p>') !!}
</div></div>


<div class="form-group{{ $errors->has('vertical') ? ' has-error' : '' }}">
<label for="salesprocess">Sales Process Step</label>
<div class="input-group input-group-lg ">

@foreach ($process as $key=>$value)

	@if(isset($document->process) && $document->process->contains('step',$value))
	<input type="checkbox" name="salesprocess[]" checked value="{{$key}}">{{$value}}
	@else
	<input type="checkbox" name="salesprocess[]"  value="{{$key}}">{{$value}}
	@endif

@endforeach

{!! $errors->first('salesprocess', '<p class="help-block">:message</p>') !!}
</div></div>