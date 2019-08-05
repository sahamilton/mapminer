@extends('admin.layouts.default')
@section('content')
<h2>Add Report</h2>
<p><a href="{{route('reports.index')}}">Back to all reports</a></p>
<div class="col-md-6">
    <form action="{{route('reports.store')}}"
        method = 'post'
        name="add report"
        id= "addReport">
        @csrf
        @include('reports.partials._form')
        <input type="submit" 
        class="btn btn-info" 
        value="Add Report" />
    </form>
</div>
@include('partials._scripts')
@endsection