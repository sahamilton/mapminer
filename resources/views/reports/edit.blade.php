@extends('admin.layouts.default')
@section('content')
<h2>Edit {{$report->report}}</h2>
<p><a href="{{route('reports.index')}}">Back to all reports</a></p>
<div class="col-md-6">
    <form action="{{route('reports.update', $report->id)}}"
        method = 'post'
        name="update report"
        id= "updateReport">
        @csrf
        @method("put")
        @bind($report)
        @include('reports.partials._form')
        @endbind
        <input type="submit" 
        class="btn btn-info" 
        value="Update Report" />
    </form>
</div>
@include('partials._scripts')
<script>
    $(document).ready(function() {
  $('#summernote').summernote();
});
</script>
@endsection