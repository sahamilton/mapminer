@extends('admin.layouts.default')
@section('content')
<div class="container">
<h2>Import Customers</h2>
First create your csv file of projects from the template.  Your import file must contain at least {{count($requiredFields)}} columns that can be mapped to:
            <ol>
            @foreach ($requiredFields as $field)
                <li style="color:red">{{$field}}</li>
            @endforeach
        </ol>


<form name="customerimport" method="post" action="{{route('customers.import')}}" 
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


 <input type="submit" name="submit" class="btn btn-info" value="Import">


</form>

</div>

@endsection