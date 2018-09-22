<!-- source -->
<?php $statuses = ['open','closed'];?>
<div class="form-horizontal{{ $errors->has('source') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Source Name</label>
    <div class="input-group input-group-lg " style="margin-bottom:10px;">
        <input required type="text" class="form-control" name='source' description="source" value="{{ old('source', isset($projectsource) ? $projectsource->source : '' )}}" placeholder="source">
        <span class="help-block">
            <strong>{{ $errors->has('source') ? $errors->first('source') : ''}}</strong>
        </span>
    </div>
</div>
<!-- Description -->
<div class="form-horizontal{{ $errors->has('description') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label">Description</label>
    <div class="input-group input-group-lg" style="margin-bottom:10px;">
        <textarea required class="form-control" name='description' title="description">{{ old('description', isset($projectsource) ? $projectsource->description : '')}}</textarea>

        <span class="help-block">
            <strong>{{$errors->has('description') ? $errors->first('description')  : ''}}</strong>
        </span>

    </div>
</div> 
<!-- Reference -->
<div class="form-horizontal{{ $errors->has('reference') ? ' has-error' : '' }}">
    <label class="col-md-2 control-label" for="reference">Reference</label>
    <div class="input-group input-group-lg " style="margin-bottom:10px;">
        <input type="text" class="form-control" name='reference' description="reference" value="{{ old('reference', isset($projectsource) ? $projectsource->reference : "")}}" placeholder="reference">
        <span class="help-block">
            <strong>{{ $errors->has('reference') ? $errors->first('reference') : ''}}</strong>
        </span>
    </div>
</div>


        <div class="form-group{{ $errors->has('status)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Status</label>
        <div class="input-group input-group-lg">
            <select class="form-control" name='status'>

            @foreach ($statuses as $status))
                <option value="{{$status}}">{{$status}}</option>

            @endforeach


            </select>
            <span class="help-block">
                <strong>{{ $errors->has('status') ? $errors->first('status') : ''}}</strong>
                </span>
        </div>
    </div>

<!-- Dates from / to -->

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
        <div class="input-group input-group-lg " style="margin-bottom:10px">
            <input required class="form-control" type="text" name="dateto"  id="todatepicker" 
            value="{{  old('dateto', isset($leadsource) ?  $leadsource->dateto->format('m/d/Y') : date('m/d/Y',strtotime('+3 months'))) }}"/>

            <span class="help-block">
                <strong>{{$errors->has('dateto') ? $errors->first('dateto')  : ''}}</strong>
            </span>
        </div>
    </div>
