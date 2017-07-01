<!--- Title -->
<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="title">Title</label>
        <div class="input-group input-group-lg ">
            <input type="text" required class='form-control' name="title" value="{{old('title', isset($document->title) ? $document->title :'') }}" />
            <span class="help-block">
                <strong>{{$errors->has('title') ? $errors->first('title')  : ''}}</strong>
            </span>
        </div>
</div>
<!-- /title -->
<!-- Description -->
<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="description">Description</label>
    <div class="input-group input-group-lg ">
        <textarea class='form-control' 
            required 
            name="description">{{old('description', isset($document->description) ?  $document->description : '' )}}</textarea>
        <span class="help-block">
             <strong>{{$errors->has('description') ? $errors->first('description')  : ''}}</strong>
        </span>
    </div>
</div>
<!-- /description -->
<!-- Summary -->
<div class="form-group{{ $errors->has('summary') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="summary">Summary</label>
    <div class="input-group input-group-lg ">
        <textarea class='form-control' 
            required 
            name="summary">{{old('summary', isset($document->summary) ?  $document->summary : '' )}}</textarea>
        <span class="help-block">
            <strong>{{$errors->has('summary') ? $errors->first('summary')  : ''}}</strong>
        </span>
    </div>
</div>

<!-- /Summary -->

<!-- /Available from / to -->
<legend>Available From / To</legend>\

<!-- Date From -->
<div class="form-group{{ $errors->has('from)') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="datefrom">Available From</label>
    <div class="input-group input-group-lg">
    <input class="form-control" 
        type="text" 
        name="datefrom"  
        id="fromdatepicker" 
        value="{{  old('datefrom', isset($document) ? date('m/d/Y',strtotime($document->datefrom)): date('m/d/Y')) }}"/>
    <span class="help-block">
        <strong>{{$errors->has('datefrom') ? $errors->first('datefrom')  : ''}}</strong>
    </span>
    </div>
</div>
<!-- /Date From -->
<!-- Date To -->
<div class="form-group{{ $errors->has('dateto') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label" for="dateto">Available To</label>
    <div class="input-group input-group-lg ">
        <input class="form-control" 
            type="text" 
            name="dateto"  
            id="todatepicker" 
            value="{{  old('dateto', isset($document) ? date('m/d/Y',strtotime($document->dateto)) : date('m/d/Y',strtotime('+1 years'))) }}"/>

        <span class="help-block">
            <strong>{{$errors->has('dateto') ? $errors->first('dateto')  : ''}}</strong>
        </span>
    </div>
</div>
<!-- /Date to -->
<!-- /Available from to -->

<!-- Industry verticals -->
<legend>Industry Verticals</legend>
    <div class="form-group{{ $errors->has('vertical') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label" for="vertical">Industry Verticals</label>
        <div class="input-group input-group-lg ">
            @include('documents.partials._verticals')  
            <span class="help-block{{ $errors->has('vertical') ? ' has-error' : '' }}">
                <strong>{{$errors->has('vertical') ? $errors->first('vertical')  : ''}}</strong>
            </span>
        </div>
    </div>
<!-- / Industry verticals -->

<!-- Sales process Steps -->
<legend>Sales Process Steps</legend>
    <div class="form-group{{ $errors->has('salesprocess') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label" for="salesprocess">Sales Process Steps</label>
        <div class="input-group input-group-lg ">
            @include('documents.partials._salesprocess') 
            <span class="help-block{{ $errors->has('salesprocess') ? ' has-error' : '' }}">
                <strong>{{$errors->has('salesprocess') ? $errors->first('salesprocess')  : ''}}</strong>
            </span>
        </div>
    </div>
<!-- / Sales process steps -->

<!-- Document Source -->

<legend>Source of Document (choose one)</Legend>

<!-- HTTP Location -->
    <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label" for="location" class="col-md-4 control-label">Web Location</label>
        <div class="input-group input-group-lg ">
            <input type="text"  
            class='form-control' 
            name="location" 
            value="{{old('location', isset($document->location) ? $document->location :'') }}" />
            <strong>{!! $errors->first('location', '<p class="help-block">:message</p>') !!}</strong>
        </div>
    </div>
<!-- / HTTP Location -->

<!-- File Location -->
    <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label"for="location" >Upload File Location</label>
        <div class="input-group input-group-lg ">
            <input type="file" 
            class="form-control" 
            name='file' id='file' 
            description="file" 
            value="{{  old('file' , isset($document) ? $document->location : '')}}">
            <span class="help-block">
                <strong>{{ $errors->has('file') ? $errors->first('file') : ''}}</strong>
            </span>
        </div>

    </div>
<!-- / File location -->

<!-- /Document Source -->


<input type="hidden" value= "{{auth()->user()->id}}" name="user_id" />
@include('partials._verticalsscript')   