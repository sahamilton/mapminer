@extends('site.layouts.default')
@section('content')
<h2>Closest Branches</h2>
<p><a href="{{route('salesorg')}}">Return to All Sales Org</a></p>
<h4>{{$data['number']}} closest branches within {{$data['distance']}} miles of {{$data['fulladdress']}}</h4>

@include('leads.partials.search')
@include('branches.partials._nearby')


@include('partials/_scripts')

@endsection