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


@include('documents.partials.selectors')

<input type="hidden" value= "{{\Auth::user()->id}}" name="user_id" />
   