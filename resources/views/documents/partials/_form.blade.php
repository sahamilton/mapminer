<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
<label for="title">Title</label>
<div class="input-group input-group-lg ">
<input type="text" required class='form-control' name="title" value="{{old('title', isset($document->title) ? $document->title :'') }}" />
<strong>{!! $errors->first('title', '<p class="help-block">:message</p>') !!}</strong>
</div></div>


<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
<label for="description">Description</label>
<div class="input-group input-group-lg ">
<textarea class='form-control' required name="description" >{{old('description', isset($document->description) ?  $document->description : '' )}}</textarea>
<strong>{!! $errors->first('description', '<p class="help-block">:message</p>') !!}</strong>
</div></div>


<div class="form-group{{ $errors->has('summary') ? ' has-error' : '' }}">
<label for="summary">Summary</label>
<div class="input-group input-group-lg ">
<textarea class='form-control' required name="summary" >{{old('summary', isset($document->summary) ?  $document->summary : '' )}}</textarea>
<strong>{!! $errors->first('summary', '<p class="help-block">:message</p>') !!}</strong>
</div></div>

<legend>Available From / To</legend>
<div class="form-group{{ $errors->has('from)') ? ' has-error' : '' }}">
    <label for="datefrom">Available From</label>
    <div class="input-group input-group-lg">
	<input class="form-control" type="text" name="datefrom"  id="fromdatepicker" 
value="{{  old('datefrom', isset($document) ? date('m/d/Y',strtotime($document->datefrom)): date('m/d/Y')) }}"/>

                <span class="help-block">
                <strong>{{$errors->has('datefrom') ? $errors->first('datefrom')  : ''}}</strong>
                </span>
</div>
</div>

<div class="form-group{{ $errors->has('dateto') ? ' has-error' : '' }}">
        <label for="dateto">Available To</label>
<div class="input-group input-group-lg ">
<input class="form-control" type="text" name="dateto"  id="todatepicker" 
value="{{  old('dateto', isset($document) ? date('m/d/Y',strtotime($document->dateto)) : date('m/d/Y',strtotime('+1 years'))) }}"/>

        <span class="help-block">
        <strong>{{$errors->has('dateto') ? $errors->first('dateto')  : ''}}</strong>
        </span>
</div>
</div>


<legend>Relates To:</legend>
@include('documents.partials._verticals')
@include('documents.partials._salesprocess')
<div class="form-group">
<legend>Source of Document (choose one)</Legend>
<div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
<label for="location">Web Location</label>
<div class="input-group">
<input type="text"  class='form-control' name="location" value="{{old('location', isset($document->location) ? $document->location :'') }}" />
<strong>{!! $errors->first('location', '<p class="help-block">:message</p>') !!}</strong>
</div></div>
<div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
     <label for="location" >Upload File Location</label>
     <div class="input-group">
         <input type="file" class="form-control" name='file' id='file' description="file" 
         value="{{  old('file' , isset($document) ? $document->location : '')}}">
         <span class="help-block">
             <strong>{{ $errors->has('file') ? $errors->first('file') : ''}}</strong>
             </span>
     </div>
 </div>
 </div>



<input type="hidden" value= "{{\Auth::user()->id}}" name="user_id" />
@include('partials._verticalsscript')   