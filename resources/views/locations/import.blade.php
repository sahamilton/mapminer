@extends('admin.layouts.default')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container">
<h2>Steps to import locations for  accounts:</h2>
<ol>
<li>First create your csv file from the template.  Do not change, add or delete any field / column</li>
<li>Save the CSV file locally on your computer.</li>
<li>Select the company that locations belong to from the list</li>
<li>Select the file and import</li>
</ol>


<form method="post" 
	action ="{{route('locations.import')}}" 
	enctype="multipart/form-data" 
	name = "importLocations">
{{csrf_field()}}

		<div class="form-group{{ $errors->has('company)') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Upload for account:</label>
        <div class="input-group input-group-lg ">
            <select  id ="company" class="form-control" name='company'>

            @foreach ($companies as $key=>$company))
            	<option value="{{$key}}">{{$company}}</option>

            @endforeach


            </select>
            <span class="help-block">
                <strong>{{ $errors->has('company') ? $errors->first('company') : ''}}</strong>
                </span>
        </div>
    </div>

<!--- Segments -->
		<div class="form-group{{ $errors->has('segment)') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label">Segments Available:</label>
        <div class="input-group input-group-lg ">
            <select id="segment" class="form-control" name='segment'>
            	<option value="">No segment data</option>
    
            </select>
            <span class="help-block">
                <strong>{{ $errors->has('segment') ? $errors->first('segment') : ''}}</strong>
                </span>
        </div>
    </div>





<!-- File Location -->
    <div class="form-group{{ $errors->has('upload') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label" for="field" >Upload File Location</label>
        <div class="input-group input-group-lg ">
            <input type="file" 
            class="form-control" 
            name='upload' id='upload' 
            description="upload" 
            value="{{  old('upload')}}">
            <span class="help-block">
                <strong>{{ $errors->has('upload') ? $errors->first('upload') : ''}}</strong>
            </span>
        </div>

    </div>
<!-- / File location -->
<input type="submit" class="btn btn-success" value="Import Locations" />

</form>
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
			headers:{
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			dataType: "json",
			success:function(response){
				$("#segment").empty();
                $("<option/>",{'','No Segment Data'}).appendTo("#segment");
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
