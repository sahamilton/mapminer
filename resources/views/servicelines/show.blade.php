@extends('site/layouts/default')
@section('content')
@if (Auth::user()->hasRole('Admin'))
	<div class="pull-right">
		<a href="{{{ URL::to('branch/create') }}}" class="btn btn-small btn-info iframe">
		<span class="glyphicon glyphicon-plus-sign"></span> Create New Branch!!</a>	
	</div>
@endif
<h1>All {{$serviceline->ServiceLine}} Branches</h1>
	<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
		<thead>
			<td>Branch</td>
			<td>Number</td>
			<td>Branch Address</td>
			<td>City</td>
			<td>State</td>
			<td>Manager</td>
			<td>Region</td>
			@if (auth()->user()->hasRole('Admin'))
				<td>Actions</td>
			@endif
		</thead>
		<tbody>
		@foreach($branches as $branch)
			<tr>  

				<td>
					<a href="{{route('branches.sho',$branch->id)}}">
					{{$branch->branchname}}
					</a>
				</td>
				<td>{{$branch->branchnumber}}</td>
				<td>{{$branch->street}}</td>
				<td>{{$branch->city}}</td>
				<td>
					<a href="{{route('showstate.branch',$branch->state)}}"
					title="See all {{$branch->state}} branches">
					{{$branch->state}}
					</a>
				</td>
				<td>
					@if(!is_null($branch->manager))
						<a href="{{'managed.branch',$branch->manager->id.}}" 
						title="See all branches managed by" {{$branch->manager->postName()}} ">
						{{$branch->manager->postName()}}
						</a>";
					@endif
				</td>
				<td>
					@if(! is_null($branch->region))
					
						<a href="{{route('region.show',$branch->region->id)}}"
						title="See all {{$branch->region->region}} branches">
						{{$branch->region->region}}
						</a>";
					@endif


				</td>
				@if (auth()->user()->hasRole('Admin'))
					<td>
					@include('partials/_modal')

					<div class="btn-group">
						<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
						</button>
						<ul class="dropdown-menu" role="menu">

							<li><a href="/branch/{{$branch->id}}/edit/">
							<i class="glyphicon glyphicon-pencil"></i> 
							Edit {{$branch->branchname}} Branch</a></li>

							<li><a data-href="{{route('branch.delete',$branch->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$branch->branchname}} branch" href="#">
							<i class="glyphicon glyphicon-trash"></i> 
							Delete {{$branch->branchname}} branch</a></li>
						</ul>
					</div>

					</td>
				@endif
			</tr>
		@endforeach

		</tbody>
	</table>

@include('partials/_scripts')
@stop

