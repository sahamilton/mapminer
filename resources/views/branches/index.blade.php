@extends('site/layouts/default')
@section('content')
@if (auth()->user()->hasRole('Admin'))
<div class="pull-right">
<a href="{{{ route('branches.create') }}}" class="btn btn-small btn-info iframe"><span class="glyphicon glyphicon-plus-sign"></span> Create New Branch</a>	</div>
@endif

<h1>All Branches</h1>


<?php $route ='branches.state';?>
<p><a href="{{route('branches.map')}}"><i class="glyphicon glyphicon-flag"> </i>Map View</a>
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
	{{$branch->branchnumber}}
	</td>

	<td>
	@if(count($branch->servicelines)>0)
		@foreach($branch->servicelines as $serviceline)
			
			<a href="{{route('serviceline.show',$serviceline->id)}}" 
			title="See all {{$serviceline->ServiceLine}} branches">
				{{$serviceline->ServiceLine}}
			</a>
		@endforeach
	@endif
	</td>

	<td>
			{{$branch->street}} {{$branch->address2}}
	</td>

	<td>
			{{$branch->city}}

	</td>

	<td>
			<a href="{{route('showstate.branch',$branch->state)}}"
			 title="See all {{$branch->state}} state branches">
			 	{{$branch->state}}
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
			@if(count($branch->manager)>0)
				
				@foreach ($branch->manager as $manager)
				<a href="{{route('managed.branch',$manager->id)}}"
				title="See all branchesmanaged by {{$manager->postName()}}">
				{{$manager->postName()}}</a>
				@endforeach
			@endif
	</td>
	
	<td>

		<a title= "See the {{'$branch->branchname'}} branch sales team"
		href ="{{route('showlist.salesteam',$branch->id)}}">
		{{count($branch->servicedBy)}}
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
				
				<li><a href="{{route('branches.edit',$branch->id)}}"><i class="fa fa-pencil" aria-hidden="true"> </i>Edit {{$branch->branchname}} Branch</a></li>
				<li><a data-href="{{route('branches.destroy',$branch->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$branch->branchname}} branch" href="#"><i class="fa fa-trash-o" aria-hidden="true"> </i> Delete {{$branch->branchname}} branch</a></li>
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
@stop
