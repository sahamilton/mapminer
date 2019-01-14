@extends('site.layouts.default')
@section('content')
@include('companies.partials._searchbar')
@include('maps.partials._form')
<h1>Activities</h1>  
@include('activities.partials._table')
   
@include('partials/_scripts')

@endsection