<!--- Filter Name -->

<div class="form-group @if ($errors->has('filter')) has-error @endif">
{{Form::label('filter')}}
{{Form::text('filter',isset($filter->filter) ? $filter->filter: Input::old('filter'),array('class'=>'form-control'))}}
 @if ($errors->has('filter')) <p class="help-block">{{ $errors->first('filter') }}</p> @endif
</div>

<!-- Color picker -->
<div id="cp2" class="input-group colorpicker-component @if ($errors->has('color')) has-error @endif">
{{Form::label('color')}}
{{Form::text('color',isset($filter->color) ? "#".$filter->color: Input::old('color'),array('class'=>'form-control'))}}
<span class="input-group-addon"><i></i></span>
 @if ($errors->has('color')) <p class="help-block">{{ $errors->first('color') }}</p> @endif
</div>

<!--- Filter Type -->
<div class="form-group @if ($errors->has('type')) has-error @endif">
{{Form::label('type')}}
<?php $options =['one'=>'select one of','multiple'=>'select multiple','group'=>'Group'];?>
 @foreach($options as $key=>$value)
    @if((isset($filter->type) and $filter->type == $key) or (!isset($filter->type) and $key =='multiple'))
    	<div>{{Form::radio('type',$key,true)}}{{$value}}</div>
    @else
    
		<div>{{Form::radio('type',$key,false)}}{{$value}}</div>
    @endif

@endforeach

 @if ($errors->has('type')) <p class="help-block">{{ $errors->first('type') }}</p> @endif
</div>


<!--- Filter Parent -->
<div class="form-group @if ($errors->has('parent')) has-error @endif">
{{Form::label('parent')}}

<select name='parent' class ='form-control'>

 @foreach($parents as $key=>$value)	
	@if(isset($filter->parent_id) and $filter->parent_id == $key)
		<option selected='selected' value='{{$key}}' >{{$value}}</option>
	@else
		<option value='{{$key}}' >{{$value}}</option>
    @endif
@endforeach
</select>

@if ($errors->has('parents')) <p class="help-block">{{ $errors->first('parents') }}</p> @endif
</div>


<!--- Filter Applies to -->
<div class="form-group @if ($errors->has('searchtable')) has-error @endif">
{{Form::label('Filter Applies To:')}}
<?php //$tables =['companies'=>'Companies','locations'=>'Locations'];
$tables = ['companies|vertical'=>'Vertical','locations|business'=>'Business Type','locations|segment'=>'Segment'];?>
 @foreach($tables as $key=>$value)
	<?php $filterOption = explode("|",$key);?>
    @if((isset($filter->searchtable) and $filter->searchtable == $filterOption[0])
	 and (isset($filter->searchcolumn) and $filter->searchcolumn == $filterOption[1]))
    	<div>{{Form::radio('filterOption',$key,true)}}{{$value}}</div>
    @else
    
		<div>{{Form::radio('filterOption',$key)}}{{$value}}</div>
    @endif

@endforeach
 @if ($errors->has('vertical')) <p class="help-block">{{ $errors->first('vertical') }}</p> @endif
</div>


<!--- Filter Can Be Null -->

<div class="form-group @if ($errors->has('searchcolumn')) has-error @endif">
{{Form::label('Can Be Null')}}
<?php $canbenull =[1=>'Yes',0=>'No'];?>

 @foreach($canbenull as $key=>$value)
    @if((isset($filter->canbenull) and $filter->canbenull == $key) or (!isset($filter->canbenull) and $key ==0))
    	<div>{{Form::radio('canbenull',$key,true)}}{{$value}}</div>
    @else
    
		<div>{{Form::radio('canbenull',$key)}}{{$value}}</div>
    @endif

@endforeach
 @if ($errors->has('canbenull')) <p class="help-block">{{ $errors->first('canbenull') }}</p> @endif
</div>

<!--- Filter active -->

<div class="form-group @if ($errors->has('searchcolumn')) has-error @endif">
{{Form::label('Active')}}
<?php $active =[0=>'Yes',1=>'No'];?>
 @foreach($active as $key=>$value)
    @if((isset($filter->inactive) and $filter->inactive == $key) or (!isset($filter->inactive) and $key ==0))
    	<div>{{Form::radio('inactive',$key,true)}}{{$value}}</div>
    @else
    
		<div>{{Form::radio('inactive',$key)}}{{$value}}</div>
    @endif

@endforeach
 @if ($errors->has('inactive')) <p class="help-block">{{ $errors->first('inactive') }}</p> @endif
</div>