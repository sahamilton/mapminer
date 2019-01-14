@extends('site.layouts.default')
@section('content')
@include('companies.partials._searchbar')
@include('maps.partials._form')
<h1>{{isset($title) ? $title : 'Contacts'}}</h1>  

@include('contacts.partials._table')
   
@include('partials/_scripts')

@endsection