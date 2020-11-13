
@extends('site/layouts/default')
@section('content')

<div id='results'>
	<a href="{{route('company.index')}}">Show All Companies</a>
</div>
@include('companies.partials._searchbar')
<h2>
{{$company->companyname}}
Locations
@if (isset($data['state']))
 in {{$data['state']}}
@endif
</h2>
@if(isset($data['state']))
<p><a href= "{{route('company.show',$company->id)}}" >See all {{$company->companyname}} locations</a></p>
@endif
@if($company->parent_id)
	<p><a href="{{route('company.show',$company->parent_id)}}">See parent company</a></p>
@endif

<nav>
	<div class="nav
	nav-tabs"
	id="nav-tab"
	role="tablist">
	<a class="nav-item nav-link active"
		id="nav-home-tab"
		data-toggle="tab"
		href="#nav-home"
		role="tab"
		aria-controls="nav-home"
		aria-selected="true">
		<strong>Account Locations</strong>
	</a>

	<a class="nav-item nav-link"
		id="nav-profile-tab"
		data-toggle="tab"
		href="#nav-profile"
		role="tab"
		aria-controls="nav-profile"
		aria-selected="false">
		<strong>Account Details</strong>
	</a>

	<a class="nav-item nav-link"
		id="nav-contact-tab"
		data-toggle="tab"
		href="#nav-contact"
		role="tab"
		aria-controls="nav-contact"
		aria-selected="false">
		<strong>Sales Notes</strong>
	</a>
	@if(! $company->isLeaf() )
		<a class="nav-item nav-link"
			id="nav-related-tab"
			data-toggle="tab"
			href="#nav-related"
			role="tab"
			aria-controls="nav-related"
			aria-selected="false">
			<strong>Related Accounts</strong>
		</a>
	@endif

	</div>
</nav>
<div class="tab-content" id="nav-tabContent">
	<div class="tab-pane fade show active"
		id="nav-home"
		role="tabpanel"
		aria-labelledby="nav-home-tab">
		@include('companies.partials._limited')
		@include('companies.partials._state')
		@include('partials/advancedsearch')
		@include('companies.partials._table')
	</div>


	<div class="tab-pane fade"
		id="nav-profile"
		role="tabpanel"
		aria-labelledby="nav-profile-tab">
		@if (isset($company->industryVertical->filter))
			<p>{{$company->industryVertical->filter}} Vertical</p>
		@endif
		<h4>ServiceLines:</h4>
		<ul>
			@foreach($company->serviceline as $serviceline)
				<li>{{$serviceline->ServiceLine}} </li>
			@endforeach
		</ul>
		<p><strong>Customer ID:</strong> {{$company->customer_id}}</p>

		

		@if(isset($company->managedBy->firstname))
		<p>Account managed by 
			<a href="{{route('person.show',$company->managedBy->id)}}" 
				title="See all accounts managed by {{$company->managedBy->fullName()}}">
				{{$company->managedBy->fullName()}}
			</a>
		</p>
		@endif
	</div>


	<div class="tab-pane fade"
		id="nav-contact"
		role="tabpanel"
		aria-labelledby="nav-contact-tab">
		@include('companies.partials._companyheader')
	</div>
	<div class="tab-pane fade"
		id="nav-related"
		role="tabpanel"
		aria-labelledby="nav-related-tab">
		@include('companies.partials._relatedaccounts')
	</div>
</div>

@include('partials/_modal')
@include('partials._scripts')
@endsection

