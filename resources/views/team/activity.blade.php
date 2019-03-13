@extends('site.layouts.default')
@section('content')
<h2>{{$people->first()->fullName()}} Team Mapminer Usage</h2>
<p><a href="{{route('team.export',$people->first()->id)}}">Export to Excel</a></p>
@include('team.partials._table')
@include('partials._scripts')
@endsection
