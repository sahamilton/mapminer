@extends ('admin.layouts.default')
@section('content')
<h2>Lead Details</h2>
<p><a href="{{route('leadsource.show',$lead->lead_source_id)}}">Show All </a></p>

@include('webleads.partials.map')

@include('partials/_scripts')
@stop

