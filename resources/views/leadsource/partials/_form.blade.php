<!-- source -->
<div class="form-group{{ $errors->has('source') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Source Name</label>
    <div class="input-group input-group-lg">
        <input required type="text" class="form-control" name='source' description="source" value="{{ old('source', isset($leadsource) ? $leadsource->source : '' )}}" placeholder="source">
        <span class="help-block">
            <strong>{{ $errors->has('source') ? $errors->first('source') : ''}}</strong>
        </span>
    </div>
</div>
<!-- Description -->
<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Description</label>
    <div class="input-group input-group-lg">
        <textarea required class="form-control" name='description' title="description">{{ old('description', isset($leadsource) ? $leadsource->description : '')}}</textarea>

        <span class="help-block">
            <strong>{{$errors->has('description') ? $errors->first('description')  : ''}}</strong>
        </span>

    </div>
</div> 
<!-- Reference -->
<div class="form-group{{ $errors->has('reference') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Reference</label>
    <div class="input-group input-group-lg">
        <input type="text" class="form-control" name='reference' description="reference" value="{{ old('reference', isset($leadsource) ? $leadsource->reference : "")}}" placeholder="reference">
        <span class="help-block">
            <strong>{{ $errors->has('reference') ? $errors->first('reference') : ''}}</strong>
        </span>
    </div>
</div>
<!-- Dates from / to -->
<div class="form-group">
<label>Available From / To</label>
</div>
    <div class="form-group{{ $errors->has('from)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label" for="datefrom">Available From</label>
        <div class="input-group input-group-lg">
            <input required class="form-control" type="text" name="datefrom"  id="fromdatepicker" 
            value="{{  old('datefrom', isset($leadsource) ? $leadsource->datefrom->format('m/d/Y'): date('m/d/Y')) }}"/>

            <span class="help-block">
                <strong>{{$errors->has('datefrom') ? $errors->first('datefrom')  : ''}}</strong>
            </span>
        </div>
    </div>

    <div class="form-group{{ $errors->has('dateto') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label" for="dateto">Available To</label>
        <div class="input-group input-group-lg ">
            <input required class="form-control" type="text" name="dateto"  id="todatepicker" 
            value="{{  old('dateto', isset($leadsource) ?  $leadsource->dateto->format('m/d/Y') : date('m/d/Y',strtotime('+3 months'))) }}"/>

            <span class="help-block">
                <strong>{{$errors->has('dateto') ? $errors->first('dateto')  : ''}}</strong>
            </span>
        </div>
    </div>
    <legend>Industry Verticals</legend>
    <div class="form-group{{ $errors->has('vertical') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label" for="vertical">Industry Verticals</label>
        <div class="input-group input-group-lg ">
@include('leadsource.partials._verticals')  
<span class="help-block{{ $errors->has('vertical') ? ' has-error' : '' }}">

                <strong>{{$errors->has('vertical') ? $errors->first('vertical')  : ''}}</strong>
            </span>
        </div>
    </div>
@include('partials._verticalsscript')