@extends('site/layouts/default')


{{-- Page content --}}
@section('content')
<div class="page-header">
<h3>How to sell to {{$company->companyname}}</h3>

@if (Auth::user()->hasRole('Admin'))
<div class="pull-right">
	<a href="" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create / Edit</a>
</div>

@endif
<p><a href ="{{{ URL::to('salesnotes/print/'.$company->id) }}}" target = "_blank"  ><img src="{{asset('assets/images/printer.jpg')}}">Printable view</a></p>
<?php
	$attachmentsPath ='documents/attachments/'.$company->id."/";
	$dirs = array('howtowork','compliance');
	foreach ($dirs as $dir) {
	$filename = "documents".DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.str_replace(" ","_",$company->companyname).".pdf";
	if(file_exists(public_path($filename))){
	echo "<p><a target=\"_blank\" href=\"".url($filename)."\" /><img src=\"".asset('assets/images/pdf.png')."\">Download ".$dir." document</a></p>";
	}
}?>
</div>
<div class='content'>
<div id="tabs" style="margin-top:20px">
<ul>
<?php 
$groups = App\Howtofield::select('group')->distinct()->get();

foreach ($groups as $tab) {
	echo "<li>";
   		echo "<a href=\"#" . str_replace(" ","_", $tab['group'])."\">".$tab['group']."</a>";
    echo "</li>";
}?>

</ul>	

<?php 

$group = $groups[0]->fieldname;	
?>
<div id="<?php echo $group;?>" >
<?php foreach ($data as $element) {

		if(str_replace(" ","_",$element->fields->group) != $group ){
			$group = str_replace(" ","_",$element->fields->group);?>
            </div>
            <div id="<?php echo str_replace(" ","_",$group);?>">
        <?php } 

			echo "<p>";
			switch ($element->fields->type){
				case "checkbox":
					$values = unserialize(urldecode($element->value));
					$fields =explode(',',$element->fields->values);
					echo "<strong>".$element->fields->fieldname."</strong>:<br />";
					foreach ($values as $key=>$value)
					{
						echo  "<input type=\"checkbox\" checked disabled />".$fields[$key];
						
					}
					echo "<br />";
				break;
				

				case "file":
					$values = unserialize(urldecode($element->value));
					$fields =explode(',',$element->fields->values);
					

					echo "<strong>".$element->fields->fieldname."</strong>:<br />";
					foreach ($values as $key=>$value)
					{
						if(file_exists(public_path($attachmentsPath.$value)))
						{
							echo "<p><a target=\"_blank\" href=\"".url($attachmentsPath.$value)."\" />". $key. "</a></p>";
						}
					
						
					}
					
				
				
				break;

				case "attachment":
					$files = unserialize(urldecode($element->value));

						if(is_array($files)){
					
							foreach($files as $file) {
					
								if(file_exists(public_path()."/documents/attachments/".$company->id."/".$file['filename']))
								{
									echo "<h4><a href =\"".asset("/documents/attachments/".$company->id."/".$file['filename'])."\"
									target=\"_blank\"

									title=\"Download ".$file['attachmentname'] ."\">
									<i class=\"glyphicon glyphicon-cloud-download\"> </i>
									".$file['attachmentname']."</a></h4>";
									echo "<p>". $file['description'] ."</p>";
									
								}
							}
							
						}



				break;
				
				default:
					echo "<strong>".$element->fields->fieldname ."</strong>: ". $element->value. "<br />";
				break;
				
			}
	echo "</p>";
			
}?>
		</div></div></div>
        <script>

$(function() {
  $("#tabs").tabs();
 
});
  </script>


        @stop