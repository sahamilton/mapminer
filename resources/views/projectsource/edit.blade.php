@extends('admin.layouts.default')
@section('content')
<div class="container">
<h2>Update Project Source</h2>
<form method="post" action="{{route('projectsource.update',$projectsource->id)}}" name="projectsourceform">
{{csrf_field()}}
<input type="hidden" name="_method" value="patch" />
@include('projectsource.partials._form')

<input type="submit" class="btn btn_info" value="Update Project Source"/>

</form>

</div>

@include('partials._scripts')
@endsection