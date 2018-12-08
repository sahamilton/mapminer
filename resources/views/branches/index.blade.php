@extends('site/layouts/default')
@section('content')
@if (auth()->user()->hasRole('Admin'))
<div class="float-right">
<a href="{{{ route('branches.create') }}}" class="btn btn-small btn-info btn-success iframe">

<i class="fas fa-plus-circle " aria-hidden="true"></i>

 Create New Branch</a>	</div>
@endif

<h1>All Branches</h1>


<?php $route ='branches.state';?>

<p><a href="{{route('branches.map')}}"><i class="far fa-flag" aria-hidden="true"></i>Map View</a>

@include('branches.partials._state')
@include('maps.partials._form')
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Branch</th>
	<th>Number</th>
	<th>Service Line</th>
	<th>Branch Address</th>
	<th>City</th>
	<th>State</th>
	<th>Region</th>
	<th>Manager</th>
	<th>Serviced</th>
	@can('manage_branches')
	<th>Actions</th>
	@endcan
    </th>

       
    </thead>
    <tbody>
   @foreach($branches as $branch)
    <tr>  
 
	
	<td>
		<a href="{{route('branches.show',$branch->id)}}" 
		 title="See details of branch {{$branch->branchname}}">
		{{$branch->branchname}}
		</a>
	</td>
	
	<td>
	{{$branch->id}}
	</td>

	<td>
	@if($branch->servicelines->count()>0)
		@foreach($branch->servicelines as $serviceline)
			
			<a href="{{route('serviceline.show',$serviceline->id)}}" 
			title="See all {{$serviceline->ServiceLine}} branches">
				{{$serviceline->ServiceLine}}
			</a>
		@endforeach
	@endif
	</td>

	<td>
			{{$branch->address->street}} {{$branch->address->suite}}
	</td>

	<td>
			{{$branch->address->city}}

	</td>

	<td>
			<a href="{{route('showstate.branch',$branch->address->state)}}"
			 title="See all {{$branch->address->state}} state branches">
			 	{{$branch->address->state}}
			</a>

	</td>

	<td>
			@if(!is_null($branch->region))
				<a href="{{route('region.show',$branch->region->id)}}"
				title="See all {{$branch->region->region}} region branches">
				{{$branch->region->region}}
				</a>
			@endif
			

	</td>
	<td>			
			@if($branch->manager->count()>0)
				
				@foreach ($branch->manager as $manager)
				<a href="{{route('managed.branch',$manager->id)}}"
				title="See all branchesmanaged by {{$manager->fullName()}}">
				{{$manager->fullName()}}</a>

				@endforeach
			@endif
	</td>
	
	<td>

		<a title= "See the {{$branch->branchname}} branch sales team"
		href ="{{route('showlist.salesteam',$branch->id)}}">

		</a>
	</td>
	@can('manage_branches')
	<td>
	
            
    
            <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
			
				<a class="dropdown-item"
					href="{{route('branches.edit',$branch->id)}}"><i class="far fa-edit text-info"" aria-hidden="true"> </i>Edit {{$branch->branchname}} Branch
				</a>
				<a class="dropdown-item"
				   data-href="{{route('branches.destroy',$branch->id)}}" data-toggle="modal" 
				   data-target="#confirm-delete" 
				   data-title = "{{$branch->branchname}} branch" 
				   href="#"><i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> Delete {{$branch->branchname}} branch
				</a>
			  </ul>
			</div>
		
		
    </td>
	@endcan
    </tr>
   @endforeach
    
    </tbody>
    </table>

@include('partials/_scripts')
@include('partials/_modal')
@endsection
