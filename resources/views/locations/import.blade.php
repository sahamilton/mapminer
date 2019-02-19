@extends('admin.layouts.default')
@section('content')
@php $importtypes = ['lead','project','location'] @endphp
<div class="container">
<h2>Steps to import locations for  accounts:</h2>
<ol>
<li>First create your csv file of leads from the template.  Your import file must contain at least {{count($requiredFields)}} columns that can be mapped to these fields:
            <ul>
            @foreach ($requiredFields as $field)
                <li style="color:red">{{$field}}</li>
            @endforeach
        </ul>
        </li>
        <li>Make sure there are no:
            <ul>
            <li>Commas</li>
            <li>Parentheses</li>
            <li>Quote marks</li>
            <li>Apostrophes</li>
        </ul>
         in your csv file. (<em>hint: use global find and replace</em>)</li>
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
            <option value={{null}}>No Existing Company</option>
            @foreach ($companies as $key=>$company))
            	<option 
                old('company_id') == $key ? 'selected' : ''
                value="{{$key}}">{{$company}}</option>

            @endforeach


            </select>
            <span class="help-block">
                <strong>{{ $errors->has('company') ? $errors->first('company') : ''}}</strong>
                </span>
        </div>
    </div>


<!--- Types -->
    <div class="form-group{{ $errors->has('type)') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">Types Available:</label>
        <div class="input-group input-group-lg ">
            <select id="type" class="form-control" name='type'>
                @foreach($importtypes as $type)
                    <option value="{{$type}}">{{$type}}</option>
                @endforeach
            </select>
            <span class="help-block">
                <strong>{{ $errors->has('type') ? $errors->first('type') : ''}}</strong>
                </span>
        </div>
    </div>
<!---- Description -->

    <div class="form-group{{ $errors->has('description)') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">Import Description:</label>
       
            <div class="form-group">
                <textarea name="description" class="form-control" rows="5" placeholder="Describe the import, source etc"></textarea>
            </div>
            <span class="help-block">
                <strong>{{ $errors->has('description') ? $errors->first('description') : ''}}</strong>
                </span>
        
    </div>
<!-----/ description-->
<div class="form-group{{ $errors->has('contacts)') ? ' has-error' : '' }}">
    <label class="col-md-4 control-label">With Contacts?:</label>
       
            <div class="form-group">
                <input type="checkbox" @if(old('contacts')) checked @endif name="contacts" class="form-control" />
            </div>
            <span class="help-block">
                <strong>{{ $errors->has('contacts') ? $errors->first('contacts') : ''}}</strong>
                </span>
        
    </div>
    <!-----/ description-->

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
<input type="hidden" name="type" value="locations" />
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
@endsection
