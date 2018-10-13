@extends('site/layouts/default')
@section('content')

@if (auth()->user()->hasRole('Admin'))
	<div class="pull-right">
		<a href="{{{ route('branches.create') }}}" class="btn btn-small btn-info iframe">
		
<i class="fas fa-plus-circle " aria-hidden="true"></i>

 Create New Branch!!</a>	
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
					<a href="{{route('branches.show',$branch->id)}}">
					{{$branch->branchname}}
					</a>
				</td>
				<td>{{$branch->id}}</td>
				<td>{{$branch->street}}</td>
				<td>{{$branch->city}}</td>
				<td>
					<a href="{{route('showstate.branch',$branch->state)}}"
					title="See all {{$branch->state}} branches">
					{{$branch->state}}
					</a>
				</td>
				<td>
					@if($branch->manager)
						
						@foreach ($branch->manager as $manager)
						<a href="{{'managed.branch',$manager->id}}" 
						title="See all branches managed by" {{$manager->postName()}} ">
						{{$manager->postName()}}
						@endforeach
						</a>
					@endif
				</td>
				<td>
					@if($branch->region)
						{{$branch->region->region}}
					@endif

				</td>
				@if (auth()->user()->hasRole('Admin'))
					<td>
					

					<div class="btn-group">
						<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
						<span class="caret"></span>
						<span class="sr-only">Toggle Dropdown</span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<a class="dropdown-item"
							href="{{route('branches.edit',$branch->id)}}">
							<i class="far fa-edit text-info"" aria-hidden="true"> </i>
							Edit {{$branch->branchname}} Branch</a>
							<a class="dropdown-item" data-href="{{route('branches.destroy',$branch->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$branch->branchname}} branch" href="#">
							<i class="far fa-trash-o text-danger" aria-hidden="true"> </i> 
							Delete {{$branch->branchname}} branch</a>

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

