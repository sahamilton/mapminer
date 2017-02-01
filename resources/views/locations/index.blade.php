@extends('site/layouts/default')
@section('content')
<h2>Steps to import locations for a national account</h2>
<ol>
<li>First create your csv file from the template.  Do not change, add or delete any field / column</li>
<li>Save the CSV file locally on your computer.</li>
<li>Select the company that locations belong to from the list</li>
<li>Select the file and import</li>
</ol>

{{ Form::open(array('route'=>'locations.import', 'files' => true)) }}

@if (isset($errors))
{{var_dump($errors)}}
@endif

<!--- File -->
<div class="form-group @if ($errors->has('title')) has-error @endif" >

{{Form::label('file','Upload File of locations for:',array('class'=>'control-label col-sm-2'))}}

@if ($errors->has('file')) <p class="help-block">{{ $errors->first('file') }}</p> @endif


<!--- Company -->
<div>
{{Form::select('company',$companies,'default',array('id'=>'company'))}}

@if ($errors->has('company')) <p class="help-block">{{ $errors->first('company') }}</p> @endif
</div>


<div class="form-group" style="clear:both">
{{Form::label('segment','Segments Available:',array('class'=>'control-label col-sm-2'))}}

<div>
{{Form::select('segment',array('0'=>'No segment data'),'0',array('id'=>'segment'))}}
</div>

</div>
<div>{{Form::file('upload')}}</div>
<div class="form-group @if ($errors->has('title')) has-error @endif">
{{Form::submit('Import',['class' => 'btn btn-sm btn-success'])}}
</div>
{{Form::close()}}
</div>

<script>
$(document).ready(function() 
{
	$('#company').change(function() {
		var id = $(this).val(); //get the current value's option
		
		$.ajax({
			type:'POST',
			url:'{{route("postAccountSegments")}}',
			data:{'id':id},
			dataType: "json",
			success:function(response){
				$("#segment").empty();
				$("<option/>",{value:0,text:'No segment data'}).appendTo("#segment");
			$.each( response, function( index, item ) {
           		$("<option/>",{value:index,text:item}).appendTo("#segment");

        });
				//in here, for simplicity, you can substitue the HTML for a brand new select box for countries
				//1.
				
			   //2.
			   // iterate through objects and build HTML here
			}
		});
	});
});
</script>
@stop
