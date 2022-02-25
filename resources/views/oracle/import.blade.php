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

    <x-form-select 
        class="input-group input-group-lg" 
        name="type" 
        :options="$types" 
        label="Import Type:" /> 
    <x-form-input class="input-group input-group-lg" type='number' step='all' label="Offset: " name='offset' value=2 />
   
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
