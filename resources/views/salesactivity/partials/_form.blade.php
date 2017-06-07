<!-- Campaign Title -->
<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
<label for="title">Title</label>
<input type="text" required class='form-control' name="title" value="{{isset($activity->title) ? $activity->title :'' }}" />
{!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>

<!-- Description -->

<div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
<label for="description">Campaign Description</label>
<textarea required class='form-control' data-error="Please provide some description of this campaign" name="description">{{old('description') ? old('description') : isset($activity->description) ? $activity->description :''  }}</textarea>
{!! $errors->first('description', '<p class="help-block">:message</p>') !!}
</div>

<!--- Date From -->

<div id="datepicker" class="form-group @if ($errors->has('datefrom')) has-error @endif">
<label class="control-label col-sm-2" for="datefrom">Date From:</label>
   <div class="input-group date col-sm-4">       
  <input type="text"  required name='datefrom' class="form-control"  value="{{isset($activity->datefrom) ? 
  $activity->datefrom->format('m/d/Y') : date('m/d/Y')}}" />
  <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
 </div> 
 @if ($errors->has('datefrom')) <p class="help-block">{{ $errors->first('datefrom') }}</p> @endif
</div>
<!--- Date To -->

<div id="datepicker1" class="form-group @if ($errors->has('dateto')) has-error @endif">
<label class="control-label col-sm-2" for="dateto">DateTo:</label>
          <div class="input-group date col-sm-4">
  <input type="text"  required name ='dateto' class="form-control"  value="{{isset($activity->dateto) ? $activity->dateto->format('m/d/Y') : date('m/d/Y', strtotime("+1 months",strtotime(date('m/d/Y'))))}}" />
  <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
</div>   
@if ($errors->has('dateto')) <p class="help-block">{{ $errors->first('dateto') }}</p> @endif

</div>


@include('salesactivity.partials.selectors')
   