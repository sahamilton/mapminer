@extends('site/layouts/default')
@section('content')

<div id='results'></div>

<div>
<h4> {{$company->companyname}} {{$data['segment']}} Locations Serviced By</h4>

<p><a href="{{route('company.show',$company->id)}}">
	Return to all locations of {{$company->companyname}}</a></p>


@if(isset($company->managedBy->firstname))
<p>Account managed by <a href="{{route('person.show',$company->managedBy->id)}}" title="See all accounts managed by {{$company->managedBy->postName()}}">{{$company->managedBy->postName()}}</a></p>
@endif


@include('companies.partials._servicetable')
@include('partials/_scripts')
@stop

