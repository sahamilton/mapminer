@extends('site/layouts/default')
@section('content')

<h1>{{$title}}</h1>

@include('companies.partials._searchbar')
    
@include('partials/_modal')
@include('partials/_scripts')
@endsection
