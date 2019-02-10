@extends('site/layouts/default')
@section('content')

<h1>My Watch List</h1>

<p><a href="{{route('watch.map')}}" title="Review my watch list"><i class="far fa-flag" aria-hidden="true"></i> View My Watch Map</a> 

<a href="{{route('watch.mywatchexport',auth()->user()->id)}}" title="Download my watch list as a CSV / Excel file"><i class="fas fa-cloud-download-alt" aria-hidden="true"></i></i> Download My Watch List</a></p>




@include('watch.partials._table')
@include('partials._scripts')
@endsection
