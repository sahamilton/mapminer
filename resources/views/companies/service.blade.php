@extends('site/layouts/default')
@section('content')

<div id='results'></div>

<div>
<h4> {{$company->companyname}} 
@if (isset($data['statecode']))
{{strtoupper($data['statecode'])}}
@endif
Locations Serviced By
</h4>

<p><a href="{{route('company.service',$company->id)}}">
	Return to all locations of {{$company->companyname}}</a></p>

 <a href="{{route('company.service.export',[$company->id,$data['statecode']])}}" title="Download service list as a CSV / Excel file"><i class="glyphicon glyphicon-cloud-download"></i> Export this Service List</a>
@if(isset($company->managedBy->firstname))
<p>Account managed by <a href="{{route('person.show',$company->managedBy->id)}}" title="See all accounts managed by {{$company->managedBy->postName()}}">{{$company->managedBy->postName()}}</a></p>
@endif

@include('companies.partials._limited')
<?php $route = 'company.service.select';?>
@include('companies.partials._state')
@include('companies.partials._servicetable')
@include('partials/_scripts')
@stop

