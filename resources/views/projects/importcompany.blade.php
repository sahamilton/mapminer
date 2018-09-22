@extends('admin.layouts.default')
@section('content')
<div class="container">
<h2>Import Projects Companies</h2>
First create your csv file of projects from the template.  Your import file must contain at least {{count($requiredFields)}} columns that can be mapped to:
            <ol>
            @foreach ($requiredFields as $field)
                <li style="color:red">{{$field}}</li>
            @endforeach
        </ol>

<p>This is part 2 of a two step process.  First <a href="{{route('projects.importfile')}}">import the projects</a>, then import the projectcompanies with the project contacts.</p>
<form name="projectimport" method="post" action="{{route('projects.companyimport')}}" 
enctype="multipart/form-data">
{{csrf_field()}}
<legend>File Location:</legend>

<div class="form-group{{ $errors->has('upload') ? ' has-error' : '' }}">
     <label class="col-md-2 control-label">Upload File Location</label>
     	<div class="input-group input-group-lg ">
         <input required type="file" class="form-control" name='upload' id='upload' description="upload" 
         value="{{ old('upload')}}">
         <strong>{!! $errors->first('upload', '<p class="help-block">:message</p>') !!}</strong>
     </div>
 </div>
<div class="form-group{{ $errors->has('source)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Sources</label>
       <div class="input-group input-group-lg ">
            <select class="form-control" name='source'>

            @foreach ($sources as $key=>$projectsource))
                <option @if($projectsource == $source) selected @endif value="{{$key}}">{{$projectsource}}</option>

            @endforeach


            </select>
            <span class="help-block">
                <strong>{{ $errors->has('source') ? $errors->first('source') : ''}}</strong>
                </span>
        </div>
    </div>

<input type = "hidden" name="table" value="projectcompanies" />
<input type="submit" name="submit" class="btn btn-info" value="Import">
<input type="hidden" name="type" value="projects"/>

</form>

</div>

@endsection