@extends('admin.layouts.default')
@section('content')
<div class="container">
<h2>Create New Project Source</h2>
<form method="post" action="{{route('projectsource.store')}}" name="projectsourceform">
{{csrf_field()}}
@include('projectsource.partials._form')

<input type="submit" class="btn btn_info" value="Create New Project Source"/>

</form>

</div>

@include('partials._scripts')
@endsection