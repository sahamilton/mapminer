@extends('site/layouts/default')


{{-- Page content --}}
@section('content')
<div class="page-header">
<h3>How to sell to {{$company->companyname}}</h3>
</div><br />
<div class='content'>
<?php 
$currentvalues =array();
foreach ($data as $element){

	if($element->fields->type == 'checkbox'){
		$currentvalues[$element['howtofield_id']] = unserialize(urldecode($element->value));
	}else{
		$currentvalues[$element['howtofield_id']] = 	$element['value'];
	}
	
}


$group = "";
	foreach ($fields as $field) {
		if($field->group != $group){
			$group = $field->group;
			echo "</fieldset>";
			echo "<fieldset><legend>". $group."</legend>";
		}
			
			switch ($field->type){
				case "checkbox":
				
					$values =explode(',',$field->values);
					echo "<strong>".$field->fieldname."</strong>:<br />";
					foreach ($values as $key=>$value)
					{
						
						if (isset($currentvalues[$field->id]) &&is_array($currentvalues[$field->id]) && in_array($values[$key],$currentvalues[$field->id]))
						{
							echo  "<input type=\"checkbox\" checked disabled />".$values[$key];
							
						}else{
							echo  "<input type=\"checkbox\"  disabled />".$values[$key];
						}
					}
					echo "<br />";
					break;
				
				default:
					echo "<strong>".$field->fieldname ."</strong><br />";
					if (array_key_exists($field->id,$currentvalues))
					{
						echo $currentvalues[$field->id];
					}
				echo "<br />";
				break;
				
			}
	
			
}	
echo "</fieldset>";	?>
		</div></div>
       
<<<<<<< HEAD
        @stop
=======
        @endsection
>>>>>>> development
