@extends('admin.layouts.default')
@section('content')
@php $importtypes = ['location'] @endphp
<div class="container">
<h2>Steps to import Oracle HR data:</h2>
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
<li>Select the file and import</li>
</ol>


<form method="post" 
	action ="{{route('oracle.import')}}" 
	enctype="multipart/form-data" 
	name = "importOracle">
    @csrf
    <div class="form-group{{ $errors->has('truncate') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label" for="truncate" >Import Type</label>
        <div class="input-group input-group-lg ">
            <select name="type"
            class="form-control
            col-md-4 " >
                @foreach ($types as $type)
                    <option value="{{$type}}">{{ucwords($type)}}</option>

                @endforeach

            </select>
            
            <span class="help-block">
                <strong>{{ $errors->has('upload') ? $errors->first('upload') : ''}}</strong>
            </span>
        </div>

    </div>
   
<div class="form-group{{ $errors->has('upload') ? ' has-error' : '' }}">
        <label class="col-md-4 control-label" for="field" >Upload File Location</label>
        <div class="input-group input-group-lg ">
            <input type="file" 
            class="form-control" 
            required
            name='upload' id='upload' 
            description="upload" 
            value="{{  old('upload')}}">
            <span class="help-block">
                <strong>{{ $errors->has('upload') ? $errors->first('upload') : ''}}</strong>
            </span>
        </div>

    </div>
		




<!-- / File location -->
<input type="submit" class="btn btn-success" value="Import Oracle HR" />

</form>
</div>


@endsection
