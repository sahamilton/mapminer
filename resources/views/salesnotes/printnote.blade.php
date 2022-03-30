@extends('site/layouts/default')


{{-- Page content --}}
@section('content')
<div class="page-header">
<h3>Sales Notes for {{$company->companyname}}</h3>
</div><br />
<div class='content'>
<?php 
$currentvalues =array();
foreach ($company->salesnotes as $element){
    
    if($element->type == 'checkbox'){
        $currentvalues[$element['id']] = unserialize(urldecode($element->pivot->fieldvalue));
    }else{
        $currentvalues[$element['id']] =     $element->pivot->fieldvalue;
    }
    
}


$group = "";
    foreach ($fields as $field) {
        if($field->fieldgroup != $group){
            $group = $field->fieldgroup;
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
echo "</fieldset>"; ?>
        </div></div>
       

        @endsection

