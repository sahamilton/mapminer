@extends('site.layouts.default')
@section('content')

<h1>My Closed Leads</h1>
<p><a href="{{route('myleads.index')}}">Return to all my leads</a></p>

@include('myleads.partials._tablist')

	
   
@include('partials._scripts')
@endsection
