<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
<label for="title">Title</label>
<div class="input-group input-group-lg ">
<input type="text" required class='form-control' name="title" value="{{isset($document->title) ? $document->title :'' }}" />
{!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div></div>


<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
<label for="description">Description</label>
<div class="input-group input-group-lg ">
<textarea class='form-control' required name="description" >{{isset($document->description) ?  $document->description : '' }}</textarea>
{!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div></div>


<div class="form-group{{ $errors->has('summary') ? ' has-error' : '' }}">
<label for="summary">Summary</label>
<div class="input-group input-group-lg ">
<textarea class='form-control' required name="summary" >{{isset($document->summary) ?  $document->summary : '' }}</textarea>
{!! $errors->first('summary', '<p class="help-block">:message</p>') !!}
</div></div>


<div class="form-group{{ $errors->has('link') ? ' has-error' : '' }}">
<label for="link">Link</label>
<div class="input-group">
<input type="text" required class='form-control' name="link" value="{{isset($document->link) ? $document->link :'' }}" />
{!! $errors->first('link', '<p class="help-block">:message</p>') !!}
</div></div>


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

<input type="hidden" value= "{{\Auth::user()->id}}" name="user_id" />
   