
<!--- Title -->
<div class="form-group @if ($errors->has('title')) has-error @endif">
{{Form::label('title','Title:',array('class'=>'control-label col-sm-2'))}}

{{Form::text('title',isset($news->title) ? $news->title :'')}}

@if ($errors->has('title')) <p class="help-block">{{ $errors->first('title') }}</p> @endif
</div>

<!--- Article -->
<div class="form-group @if ($errors->has('news')) has-error @endif">
{{Form::label('news','News Article:',array('class'=>'control-label col-sm-2'))}}
<div class="input-group date col-sm-4">
{{Form::textarea('news',isset($news->news) ? $news->news :'',array('class'=>'summernote'))}}

@if ($errors->has('news')) <p class="help-block">{{ $errors->first('news') }}</p> @endif
</div></div>
 
<!--- Date From -->

<div id="datepicker" class="form-group @if ($errors->has('startdate')) has-error @endif">
<label class="control-label col-sm-2" for="startdate">Date From:</label>
   <div class="input-group date col-sm-4">       
  <input type="text" name='startdate' name='startdate' class="form-control" readonly value="{{isset($news->startdate) ? date('m/d/Y',strtotime( 
  $news->startdate)) : date('m/d/Y')}}" />
  <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
 </div> 
 @if ($errors->has('startdate')) <p class="help-block">{{ $errors->first('startdate') }}</p> @endif
</div>
 
<!--- Date To -->

<div id="datepicker1" class="form-group @if ($errors->has('enddate')) has-error @endif">
<label class="control-label col-sm-2" for="edndate">DateTo:</label>
          <div class="input-group date col-sm-4">
  <input type="text" name='enddate' name ='enddate' class="form-control" readonly value="{{isset($news->enddate) ? date('m/d/Y',strtotime( 
  $news->enddate)): date('m/d/Y', strtotime("+3 months",strtotime(date('m/d/Y'))))}}" />
  <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
</div>   
@if ($errors->has('enddate')) <p class="help-block">{{ $errors->first('enddate') }}</p> @endif

</div>

<!--- Service Line -->

<div class="form-group @if ($errors->has('serviceline')) has-error @endif">
{{Form::label('ServiceLine','Service Lines:', array('class'=>'control-label col-sm-2'))}}

<div class="input-group date col-sm-4">
{{Form::select('serviceline[]',$servicelines,isset($news) ? $news->serviceline->pluck('id') : '',array('class'=>'form-control','multiple'=>true))}}

@if ($errors->has('serviceline')) <p class="help-block">{{ $errors->first('serviceline') }}</p> @endif
</div>

<div>

<input type="hidden" name="user_id" value="{{auth()->user()->id}}" />
