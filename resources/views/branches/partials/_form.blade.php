<div>
{{Form::label('branchnumber','Branch Number:')}}
<div>
{{Form::text('branchnumber')}}
{{ $errors->first('branchnumber') }}
</div></div>

<div>
{{Form::label('branchname','Branch Name:')}}
<div>
{{Form::text('branchname')}}
{{ $errors->first('branchname') }}
</div></div>


<div>
{{Form::label('street','Street:')}}
<div class="controls">
{{Form::text('street')}}
{{ $errors->first('stree') }}
</div></div>

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
{{Form::select('state',Form::states())}}
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
			<a class="btn btn-link" href="{{ route('branch.index') }}">Cancel</a>

			<button type="reset" class="btn">Reset</button>

			<button type="submit" class="btn btn-success">{{$buttonLabel}}</button>
		</div>
	</div>
	</div>