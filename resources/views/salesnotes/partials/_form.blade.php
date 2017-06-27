@foreach($data as $field)

@if($field['group'] != $group)
</div>
	<div id="{{str_replace(" ","_",$field['group'])}}">
	<?php $group = $field['group'];?>
    @endif

<div class="form-group{{ $errors->has($field['id']) ? ' has-error' : '' }}">
<label class="col-md-4 control-label">{{$field['fieldname']}}</label>

<div class="input-group input-group-lg ">
<?php 

switch ($field['type']) {
	
	case ('text'):
	echo Form::text($field['id'],$field['value'] );
	
	break;
	
	case ('textarea'):
	echo Form::textarea($field['id'],$field['value']);
	break;
	
	case ('select'):
	$options = explode(",",$field['values']);

	echo Form::select($field['id'],$options,$field['value']);
	break;
	
	case ('radio'):
	$options = explode(",",$field['values']);
	$selected = explode(",",$field['value']);
	if(is_array($options)){
		foreach($options as $value) {
			echo $value." ";
			if(in_array($value,$selected)){
				echo Form::radio($field['id'],$value,TRUE);
			}else{
				echo Form::radio($field['id'],$value);
			}
	
		}
	}
	break;
	
	case ('checkbox'):
	$options = explode(",",$field['values']);
	if(is_array($options)){
		foreach($options as $value) {
			echo $value." ";
			$fieldname = $field['id']."[]";
			if(is_array($field['value']) && in_array($value,$field['value'])){
				echo Form::checkbox($fieldname,$value,TRUE);
			}else{
				echo Form::checkbox($fieldname,$value);
			}
		}
	}
	break;
	
	case ('multiselect'):
		$options = explode(",",$field['values']);
		$selected = explode(",",$field['value']);
		$fieldname = $field['id']."[]";
		echo Form::select($fieldname,$options, $selected, array('multiple'));

	break;
	
	case ('file'):
	if(file_exists(public_path()."/documents/howtowork/".str_replace(" ","_",$company->companyname).".pdf")){
		echo "<a href =\"".asset('/documents/howtowork/'.str_replace(" ","_",$company->companyname).'.pdf')."\" >";
		echo "View How To Sell to ". $company->companyname." Notes</a><br />" ;
	}

	  
	
	
	break;

	case ('attachment'):
	$files = unserialize(urldecode($field['value']));
	echo "<input type=\"hidden\" name = \"".$field['id']. "\" value=\"".$field['value']."\"/>";
	if(is_array($files)){
		foreach($files as $file) {

			if(file_exists(public_path()."/documents/attachments/".$company->id."/".$file['filename']))
			{
				echo "<li>
				<a href= \"".route('salesnotes.filedelete',$file['filename'])."\"><i class=\"glyphicon glyphicon-trash\"></i>

				<a href =\"".asset("/documents/attachments/".$company->id."/".$file['filename'])."\">".$file['attachmentname']."</a></li>";
			}
		}
		
	}?>
		<fieldset><legend>Add New Attachments</legend>	
		<div class="form-group{{ $errors->has('attachmentname') ? ' has-error' : '' }}">
		{{Form::label('attachmentname','Name')}}
		{{Form::text('attachmentname','',['class'=>'form-control has-error'])}}
		<span class="help-block">
		        <strong>{{ $errors->has('attachmentname') ? $errors->first('attachmentname') : ''}}</strong>
		        </span>
		</div>

		<div class="form-group" >
		{{Form::label('description','Description') }}
		{{Form::textarea('attachmentdescription','',['class'=>'form-control']) }}
		</div>
		<div class="form-group{{ $errors->has('attachment') ? ' has-error' : '' }}">
		<input class ='form-control' type='file' name='attachment' />
		<span class="help-block {{ $errors->has('attachment') ? ' has-error' : ''}}">
		        <strong>{{ $errors->has('attachment') ? $errors->first('attachment') : ''}}</strong>
		        </span>
		        
		</div>

		</fieldset>

	<?php
	
	break;
	
	default:
		echo Form::text($field['id']);
		$field['type'];
	break;	
}?>
    <span class="help-block">
		        <strong>{{ $errors->has($field['id']) ? $errors->first($field['id']) : ''}}</strong>
		        </span>
		</div>
</div>

    
@endforeach
<!-- Form Actions -->
	