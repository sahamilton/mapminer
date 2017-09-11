<?php

$state = new App\State;
$states = $state->getStates();

?>

<!-- branchnumber -->
 <div class="form-group{{ $errors->has('branchnumber') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Branch Number:</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name='branchnumber' description="branchnumber" value="{{ old('branchnumber') ? old('branchnumber') : isset($data->branchnumber) ? $data->branchnumber : "" }}" placeholder="branchnumber">
                <span class="help-block">
                    <strong>{{ $errors->has('branchnumber') ? $errors->first('branchnumber') : ''}}</strong>
                    </span>
            </div>
    </div>
    
<div>
<!-- branchname -->
    <div class="form-group{{ $errors->has('branchname') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Branch Name</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name='branchname' description="branchname" value="{{ old('branchname') ? old('branchname') : isset($data->branchname) ? $data->branchname : "" }}" placeholder="branchname">
                <span class="help-block">
                    <strong>{{ $errors->has('branchname') ? $errors->first('branchname') : ''}}</strong>
                    </span>
            </div>
    </div>
    
<!-- street -->
    <div class="form-group{{ $errors->has('street') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Address:</label>
            <div class="col-md-6">
                <input type="text" class="form-control" name='street' description="street" value="{{ old('street') ? old('street') : isset($data->street) ? $data->street : "" }}" placeholder="street">
                <span class="help-block">
                    <strong>{{ $errors->has('street') ? $errors->first('street') : ''}}</strong>
                    </span>
            </div>
    </div>
    

<div>


<div>
{{Form::label('address2','Address:')}}
<div class="controls">
{{Form::text('address2')}}
{{ $errors->first('address2') }}
</div></div>

<div>
{{Form::label('city','City:')}}
<div class="controls">
{{Form::text('city')}}
{{ $errors->first('city') }}
</div></div>

<div>
{{Form::label('state','State')}}
<div>
{{Form::select('state',$states)}}
{{ $errors->first('state') }}
</div></div>

<div>
{{Form::label('zip','ZIP Code:')}}
<div class="controls">
{{Form::text('zip')}}
{{ $errors->first('zip') }}
</div></div>


<div>

<div>
{{Form::label('radius','Service Radius (in miles):')}}
<div class="controls">
{{Form::text('radius')}}
{{ $errors->first('radius') }}
</div></div>


<div>

<fieldset><legend>Service Lines:</legend>

<div>

 <?php foreach ($servicelines as $line){
	 
	echo "<br />";
   if (isset($served) && is_array($served) && in_array($line->id,$served)){
       
           echo  Form::checkbox('serviceline['.$line->id.']',1,true);
   }else{
           echo  Form::checkbox('serviceline['.$line->id.']', 1,false);
   }
   echo "  ".  Form::label('serviceline',$line->ServiceLine);
 }?>
 
</div>
</fieldset>

<div>
{{Form::label('region_id','Region:')}}
<div>
{{Form::select('region_id', array(
    '1'=>'Western' ,'2'=>'CLP','3'=>'Eastern','4'=>'Mid-America & Canada
'))}}
{{ $errors->first('brand') }}
</div></div>

<div>
{{Form::label('person_id','Managed By:')}}
<div>
{{Form::select('person_id', $managers)}}
{{ $errors->first('brand') }}
</div></div>


<!-- Form Actions -->
	<div style="margin-top:20px">
		<div class="controls">


			<button type="submit" class="btn btn-success">{{$buttonLabel}}</button>
		</div>
	</div>
	</div>