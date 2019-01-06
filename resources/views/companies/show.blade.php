@extends('site/layouts/default')
@section('content')

<div id='results'></div>
@include('companies.partials._searchbar')
<h2> {{$data['company']->companyname}} Locations </h2>
@include('maps.partials._form')
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
	  <a class="nav-link nav-item active" 
	      id="locations-tab" 
	      data-toggle="tab" 
	      href="#locations" 
	      role="tab" 
	      aria-controls="locations" 
	      aria-selected="true">
	    <strong>Account Locations</strong>
	  </a>
	  <a class="nav-item nav-link"  
	        data-toggle="tab" 
	        href="#details"
	        id="details-tab"
	        role="tab"
	        aria-controls="details"
	        aria-selected="false">

	    <strong>Account Details</strong>
	  </a>
	    <a class="nav-item nav-link"  
	        data-toggle="tab" 
	        href="#actions"
	        id="action-tab"
	        role="tab"
	        aria-controls="actions"
	        aria-selected="false">

	    <strong>Account Actions</strong>
	  </a>

	</div>
</nav>

<div class="tab-content" id="nav-tabContent">
    <div id="locations" class="tab-pane show active">
    	@include('partials/advancedsearch')
    	@include('companies/partials/_state')
     	@include('companies.partials._limited')
		@include('companies.partials._table')
    </div>
    <div id="details" class="tab-pane fade">
    	@include('companies.partials._companyheader')
		@if (isset($data['company']->industryVertical->filter))
			<p>{{$data['company']->industryVertical->filter}} Vertical</p>
		@endif
		<h4>ServiceLines:</h4>
		<ul>
		@foreach($data['company']->serviceline as $serviceline)
			<li>{{$serviceline->ServiceLine}} </li>
		@endforeach
		</ul>



		@if(isset($data['company']->managedBy->firstname))
			<p>Account managed by <a href="{{route('person.show',$data['company']->managedBy->id)}}" title="See all accounts managed by {{$data['company']->managedBy->fullName()}}">
				{{$data['company']->managedBy->fullName()}}
			</a>
		</p>
		@endif
      

    </div>
    <div id="actions" class="tab-pane fade">
     @if (auth()->user()->can('manage_accounts'))
		<p>
			<i class="fab fa-pagelines"></i> 
			<a href= "{{route('company.service',$data['company']->id)}}">Show Service Branch Details</a>
		</p>
		<p>
			<i class="fas fa-users"></i> 
			<a href= "{{route('company.teamservice',$data['company']->id)}}">Show Service Team Details</a>
		</p>
		@if (auth()->user()->hasRole('Admin'))


<div class="float-right" style="margin-bottom:20px">
				<a href="{{route('company.location.create',$data['company']->id)}}" title="Create a new {{$data['company']->companyname}} location" class="btn btn-small btn-info iframe">
				

<i class="fas fa-plus-circle " aria-hidden="true"></i>


				 Create New Location</a>
			</div>
@endif
	@endif
    </div>

   

  </div>

















@include('partials/_modal')
@include('partials._scripts')
@endsection

