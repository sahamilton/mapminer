@extends('site.layouts.default')
@section('content')
<h2>Leads for direct reports of {{$leads->postName()}}</h2>
@include('salesleads.partials._managerleads')


@include('partials/_scripts')



@stop