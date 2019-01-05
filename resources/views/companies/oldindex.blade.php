@extends('site/layouts/default')
@section('content')

<h1>{{$title}}</h1>
@if($title != 'All Accounts')
<p><a href="{{route('company.index')}}" title="See all accounts">See all accounts</a></p>
@endif


@include('partials/_showsearchoptions')
@include('partials/advancedsearch')
@include('partials.companyfilter')


@if (auth()->user()->hasRole('Admin') or auth()->user()->hasRole('Sales Operations'))



<div class="float-right">
<a href="{{ route('company.create') }}" class="btn btn-small btn-info iframe">

<i class="fas fa-plus-circle " aria-hidden="true"></i>

 Create New Account</a>
</div>
@endif

	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<th>Company</th>
			<th>Customer Id</th>
			<th>Manager</th>
			<th>Email</th>
			<th>Vertical</th>
			<th>Locations</th>
			<th>Service Lines</th>


			@if (auth()->user()->hasRole('Admin') or auth()->user()->hasRole('Sales Operations'))

			<th>Actions</th>
			@endif
		</thead>
	<tbody>
	@foreach($companies as $company)

		<tr>

			<td>
			@if($company->locations_count > 0)

			<a href="{{route('company.show',$company->id)}}"
			title = 'See all {{$company->companyname}} locations'>{{$company->companyname}}</a>
			@else
			<span title="{{$company->companyname}} has no locations">{{$company->companyname}}</span>
			@endif
			</td>
			<td>{{$company->customer_id}}</td>
			<td>
			@if(isset($company->managedBy))
			<a href="{{route('person.show',$company->managedBy->id)}}"
			title="See all companies managed by {{$company->managedBy->fullName()}}" >
			{{$company->managedBy->fullName()}}
			</a>
			@endif
			</td>
			<td>
			@if(isset($company->managedBy->userdetails))
			<a href="mailto:{{$company->managedBy->userdetails->email}}"
				title="Email {{$company->managedBy->fullName()}}" >
				{{$company->managedBy->userdetails->email}}
			</a>
			@endif
			</td>
			<td>
			@if(isset($company->industryVertical))

			<a href="{{route('company.vertical',$company->industryVertical->id)}}"
			title ="See all {{$company->industryVertical->filter}} companies">
			{{$company->industryVertical->filter}}
			</a>
			@endif
			</td>
			<td>
				@if($company->locations_count>0)
				<a href="{{route('company.show',$company->id)}}"
				title = 'See all {{$company->companyname}} locations'>
					{{number_format($company->locations_count,0)}}
				</a>
				@endif
		</td>
			<td>
			<ul>
				@foreach ($company->serviceline as $serviceline)

				<li><a href="{{route('serviceline.accounts',[$serviceline->id,'co'])}}"
				title="See all {{$serviceline->ServiceLine}} companies" >
				{{$serviceline->ServiceLine}}
				</a></li>
				@endforeach
				</ul>
			</td>

			@if (auth()->user()->hasRole('Admin') or auth()->user()->hasRole('Sales Operations'))
				<td>


					<div class="btn-group">
						<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
						</button>
						<ul class="dropdown-menu" role="menu">

						
						
						<a class="dropdown-item" 
						title="Edit {{$company->companyname}}"
						  href="{{route('company.edit',$company->id)}}">
						<i class="far fa-edit text-info"" aria-hidden="true"> </i>
						Edit {{$company->companyname}}</a>
						
						<a class="dropdown-item"
						title="Delete {{$company->companyname}} and all its locations"
						  data-href="{{route('company.destroy',$company->id)}}" 
						  data-toggle="modal" 
						  data-target="#confirm-delete" 
						  data-title = "{{$company->companyname}} and all its locations" 
						  href="#">
						  <i class="far fa-trash-alt text-danger" 
						    aria-hidden="true"> </i>
						   Delete {{$company->companyname}}
						</a>

						</ul>
					</div>
				</td>
			@endif
		</tr>

	@endforeach

	</tbody>
	</table>
@include('partials/_modal')
@include('partials/_scripts')
@endsection
