@extends('admin.layouts.default')
@section('content')
<div class="container">
<form name="projectimport" method="post" action="{{route('projects.bulkimport')}}" 
enctype="multipart/form-data">
{{csrf_field()}}
<legend>File Location:</legend>
<div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
     <label for="location" >Upload File Location</label>
     
         <input required type="file" class="form-control" name='file' id='file' description="file" 
         value="{{ old('file')}}">
         <strong>{!! $errors->first('file', '<p class="help-block">:message</p>') !!}</strong>
     </div>
 </div>

 <input type="submit" name="submit" class="btn btn-info" value="Import">

</form>

</div>

@endsection