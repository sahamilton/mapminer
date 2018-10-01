<div>
{{Form::label('fieldname','Field Name:')}}
<div class="controls">
{{Form::text('fieldname')}}
{{ $errors->first('fieldname') }}
</div></div>



<div>
{{Form::label('required','Required:')}}
<div class="controls">
{{Form::checkbox('required','1',false)}}
{{ $errors->first('required') }}
</div></div>

<div>
<?php $types =array('text'=>'text',
'textarea'=>'textarea',
'file'=>'file',
'select'=>'select',
'multiselect'=>'multiselect',
'checkbox'=>'checkbox',
'radio'=>'radio');?>
{{Form::label('type','Type:')}}
<div class="controls">
{{Form::select('type',$types,isset($howtofield->type) ? $howtofield->type : 'text',$attributes=['size'=>'3'])}}
{{ $errors->first('type') }}
</div></div>

<div>

{{Form::label('values','Values:')}}
<div class="controls">
{{Form::textarea('values')}}
{{ $errors->first('values') }}
</div></div>

<div>

{{Form::label('group','Group:')}}
<div class="controls">
{{Form::select('group',$groupsSelect,isset($howtofield->group) ? $howtofield->group : head($groupsSelect),$attributes=['size'=>'3'])}}
{{ $errors->first('groups') }}
<p style ="margin-top: 10px">
<input type='text' id="addGroup" name='addGroup' /><button type="button"  id="add" >  <i class="fas fa-plus text-success" aria-hidden="true"></i> Add Group</button></p>
</div></div>




<!-- Form Actions -->
	<div style="margin-top:20px">
		<div class="controls">
			<a class="btn btn-link" href="{{ route('company.index') }}">Cancel</a>

			<button type="reset" class="btn">Reset</button>

			<button type="submit" class="btn btn-success">{{$buttonLabel}}</button>
		</div>
	</div>
    
   