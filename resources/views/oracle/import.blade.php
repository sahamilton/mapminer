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

    <x-form-select class="input-group input-group-lg" name="type" :options='$types'
label="Import type:" />
     <x-form-input class="input-group input-group-lg" type='number' step='all' label="Offset: " name='offset' value=2 /> 
     <x-form-input type="file" name="upload" label="Upload CSV file:" class="input-group input-group-lg" />
    
<!-- / File location -->
<input type="submit" class="btn btn-success" value="Import Oracle HR" />

</form>
</div>


@endsection
