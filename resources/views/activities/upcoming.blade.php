@extends('site.layouts.default')
@section('content')
<h2>{{$title}}</h2>
@include('activities.partials._table')
@include('partials._scripts')
@endsection