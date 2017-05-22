<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
<label for="title">Title</label>
<input type="text" required class='form-control' name="title" value="{{isset($activity->title) ? $activity->title :'' }}" />
{!! $errors->first('title', '<p class="help-block">:message</p>') !!}
</div>

<!--- Date From -->

<div id="datepicker" class="form-group @if ($errors->has('from')) has-error @endif">
<label class="control-label col-sm-2" for="from">Date From:</label>
   <div class="input-group date col-sm-4">       
  <input type="text" name='from' required name='from' class="form-control" readonly value="{{isset($activity->from) ? date('m/d/Y',strtotime( 
  $activity->from)) : date('m/d/Y')}}" />
  <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
 </div> 
 @if ($errors->has('from')) <p class="help-block">{{ $errors->first('from') }}</p> @endif
</div>
<!--- Date To -->

<div id="datepicker1" class="form-group @if ($errors->has('to')) has-error @endif">
<label class="control-label col-sm-2" for="edndate">DateTo:</label>
          <div class="input-group date col-sm-4">
  <input type="text" name='to' required name ='to' class="form-control" readonly value="{{isset($activity->to) ? date('m/d/Y',strtotime( 
  $activity->to)): date('m/d/Y', strtotime("+1 months",strtotime(date('m/d/Y'))))}}" />
  <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
</div>   
@if ($errors->has('to')) <p class="help-block">{{ $errors->first('to') }}</p> @endif

</div>


@include('salesactivity.partials.selectors')
   