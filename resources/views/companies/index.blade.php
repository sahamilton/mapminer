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


<div class="pull-right">
<a href="{{ route('company.create') }}" class="btn btn-small btn-info iframe">
<i class="far fa-plus-circle " aria-hidden="true"></i>
 Create New Account</a>
</div>
@endif

	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<th>Company</th>
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
			@if(count($company->countlocations)>0)

			<a href="{{route('company.show',$company->id)}}"
			title = 'See all {{$company->companyname}} locations'>{{$company->companyname}}</a>
			@else
			<span title="{{$company->companyname}} has no locations">{{$company->companyname}}</span>
			@endif
			</td>
			<td>
			@if(isset($company->managedBy))
			<a href="{{route('person.show',$company->managedBy->id)}}"
			title="See all companies managed by {{$company->managedBy->postName()}}" >
			{{$company->managedBy->postName()}}
			</a>
			@endif
			</td>
			<td>
			@if(isset($company->managedBy->userdetails))
			<a href="mailto:{{$company->managedBy->userdetails->email}}"
				title="Email {{$company->managedBy->postName()}}" >
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
				@if(count($company->countlocations)>0)
				<a href="{{route('company.show',$company->id)}}"
				title = 'See all {{$company->companyname}} locations'>
					{{number_format($company->countlocations->first()->count,0)}}
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
						  href="{{route('company.edit',$company->id)}}">
						<i class="far fa-edit text-info"" aria-hidden="true"> </i>
						Edit {{$company->companyname}}</a>
						
						<a class="dropdown-item"
						  data-href="{{route('company.destroy',$company->id)}}" 
						  data-toggle="modal" 
						  data-target="#confirm-delete" 
						  data-title = "{{$company->companyname}} and all its locations" 
						  href="#">
						  <i class="far fa-trash-o text-danger" 
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
