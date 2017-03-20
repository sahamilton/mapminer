@foreach($data as $field)

@if($field['group'] != $group)
</div>
	<div id="{{str_replace(" ","_",$field['group'])}}">
	<?php $group = $field['group'];?>
    @endif


<div class="controls">
{{Form::label($field['id'],$field['fieldname'])}}
<div>
<?php 

switch ($field['type']) {
	
	case ('text'):
	echo Form::text($field['id'],$field['value']);
	
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
		
	}
	

	
	
		echo "<fieldset><legend>Add New Attachments</legend>";

		
		echo "<div class=\"form-group \">"; 
		
		
		
		echo Form::label('attachmentname','Name') ;
		echo Form::text('attachmentname','',array('class'=>'form-control has-error'));
		echo "</div>";
		echo "<div class=\"form-group\" >";
		echo Form::label('description','Description') ;
		echo Form::textarea('attachmentdescription','',array('class'=>'form-control')) ;
		echo "</div>";?>
		<div class="form-group" >
		<input class ='form-control' type='file' name='attachment' />
		</div>
		</fieldset>

	<?php
	
	break;
	
	default:
		echo Form::text($field['id']);
		$field['type'];
	break;	
}?>
    {{ $errors->first($field['id']) }}
    </div></div>

    
@endforeach
<!-- Form Actions -->
	</div><div style="margin-top:20px">
		<div class="controls">

			<button type="submit" class="btn btn-success">Edit Notes</button>
		</div>
	</div>