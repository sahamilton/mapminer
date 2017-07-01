<!-- Campaign Title -->
<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
<label class="col-md-4 control-label" for="title">Title</label>
<div class="input-group input-group-lg col-md-8">
<input type="text" required class='form-control' name="title" value="{{old('title', isset($activity->title) ? $activity->title :'' )}}" />
{!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>
</div>

<!-- Description -->

<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
<label class="col-md-4 control-label" for="description">Campaign Description</label>
<div class="input-group input-group-lg col-md-8">
<textarea required class='form-control' data-error="Please provide some description of this campaign" name="description">{{old('description', isset($activity->description) ? $activity->description :''  )}}</textarea>
{!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>
</div>
<legend>Dates Available</legend>
<!--- Date From -->

<div id="datepicker" class="form-group @if ($errors->has('datefrom')) has-error @endif">
<label class="control-label col-sm-4" for="datefrom">Date From:</label>
<div class="input-group date input-group-lg">       
<input type="text"  required name='datefrom' class="form-control"  value="{{old('datefrom', isset($activity->datefrom) ? 
$activity->datefrom->format('m/d/Y') : date('m/d/Y'))}}" />
<span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
</div> 
@if ($errors->has('datefrom')) <p class="help-block">{{ $errors->first('datefrom') }}</p> @endif
</div>
<!--- Date To -->

<div id="datepicker1" class="form-group @if ($errors->has('dateto')) has-error @endif">
<label class="control-label col-sm-4" for="dateto">DateTo:</label>
<div class="input-group date input-group-lg">
<input type="text"  required name ='dateto' class="form-control"  value="{{old('dateto',isset($activity->dateto) ? $activity->dateto->format('m/d/Y') : date('m/d/Y', strtotime("+1 months",strtotime(date('m/d/Y')))))}}" />
<span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
</div>   
@if ($errors->has('dateto')) <p class="help-block">{{ $errors->first('dateto') }}</p> @endif

</div>
<legend>Industry Verticals</legend>
	<div class="form-group{{ $errors->has('vertical') ? ' has-error' : '' }}">
		<label class="col-md-4 control-label" for="title">Industry Vertical</label>
		<div class="input-group input-group-lg ">
			@include('salesactivity.partials._verticals')
		 	<span class="help-block{{ $errors->has('salesprocess') ? ' has-error' : '' }}">
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
            @include('salesactivity.partials._salesprocess') 
            <span class="help-block{{ $errors->has('salesprocess') ? ' has-error' : '' }}">
                <strong>{{$errors->has('salesprocess') ? $errors->first('salesprocess')  : ''}}</strong>
            </span>
        </div>
    </div>
<!-- / Sales process steps --> 
@include('partials._verticalsscript') 