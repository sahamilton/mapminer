@extends('site/layouts/default')
@section('content')

<div id='results'></div>

<div>
<h2> {{$company->companyname}} {{$data['segment']}} Locations </h2>
<p>

	<a href= "{{route('company.service',$company->id)}}">Show Service Details</a></p>

@if (isset($company->industryVertical->filter))
	<p>{{$company->industryVertical->filter}} Vertical</p>
@endif
<h4>ServiceLines:</h4>
<ul>
@foreach($company->serviceline as $serviceline)
	<li>{{$serviceline->ServiceLine}} </li>
@endforeach
</ul>

@include('companies.partials._segment')

@if(isset($company->managedBy->firstname))
<p>Account managed by <a href="{{route('person.show',$company->managedBy->id)}}" title="See all accounts managed by {{$company->managedBy->postName()}}">{{$company->managedBy->postName()}}</a></p>
@endif
@if (auth()->user()->hasRole('Admin'))


<div class="float-right" style="margin-bottom:20px">
				<a href="{{route('company.location.create',$company->id)}}" title="Create a new {{$company->companyname}} location" class="btn btn-small btn-info iframe">
				

<i class="fas fa-plus-circle " aria-hidden="true"></i>


				 Create New Location</a>
			</div>
@endif
         
@include('companies.partials._companyheader')
@include('partials/advancedsearch')
@include('companies/partials/_state')
@include('maps.partials._form')
@include('companies.partials._limited') 
@include('companies.partials._table')
@include('partials/_modal')
@include('partials/_scripts')
@endsection

