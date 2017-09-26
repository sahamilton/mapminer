@extends('admin.layouts.default')
@section('content')
<div class="container">
<h2>Import Projects</h2>
<p>This is a three step process.  First import the projects, then import the projectcompanies and finally the project contacts.</p>
<form name="projectimport" method="post" action="{{route('projects.bulkimport')}}" 
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
    <?php $tables = ['projects','projectcompanies'];?>
        <div class="form-group{{ $errors->has('table)') ? ' has-error' : '' }}">
        <label class="col-md-2 control-label">Tables</label>
        <div class="input-group input-group-lg ">
            <select  class="form-control" name='table'>

            @foreach ($tables as $table))
                <option value="{{$table}}">{{$table}}</option>

            @endforeach


            </select>
            <span class="help-block">
                <strong>{{ $errors->has('table') ? $errors->first('table') : ''}}</strong>
                </span>
        </div>
    </div>


 <input type="submit" name="submit" class="btn btn-info" value="Import">
 <input type="hidden" name="type" value="projects"/>

</form>

</div>

@endsection