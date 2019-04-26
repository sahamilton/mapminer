@extends('site.layouts.default')
@section('content')
<h2>{{$title}}</h2>
 <p><a href="{{route('dashboard.index')}}">Return To Branch Dashboard</a></p>
@include('activities.partials._upcoming')
@include('partials._scripts')
@endsection