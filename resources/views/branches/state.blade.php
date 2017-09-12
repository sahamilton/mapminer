@extends('site/layouts/default')
@section('content')

<h2>{{$data['fullstate']}} State Branches</h2>
<h4> <a href="{{route('branches.index')}}" title="Show all branches" />Show all branches</a></h4>
<?php $route='branches.state';?>
@include('branches.partials._state')
<p><a href="{{route('branches.showstatemap',$data['state'])}}">
<i class="glyphicon glyphicon-flag"></i> Map view</a></p>
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
		<th>Sales Team</th>
		@if(Auth::user()->hasRole('Admin'))
			<th>Actions</th>
		@endif 
	</thead>
    <tbody>
		@foreach($branches as $branch)
    <tr>  
	<td>
		<a href="{{route('branches.show',$branch->id)}}" 
		title="See details of {{$branch->branchname}} branch">
		{{$branch->branchname}}
		</a>
	</td>
	<td>
		{{$branch->branchnumber}}
	</td>
	<td>
		@foreach($branch->servicelines as $serviceline)
				<a href = "{{route('serviceline.show',$serviceline->id)}}" 
				title =" See all {{$serviceline->ServiceLine}} branches">
				{{$serviceline->ServiceLine}}
				</a>
		@endforeach
	</td>
	<td>
		{{$branch->street}} {{$branch->address2}}
	</td>

	<td>
		{{$branch->city}}
	</td>
	<td>
		{{$branch->state}}
	</td>
	<td>
		<a href="{{route('region.show',$branch->region->id)}}" 
		title="See all {{$branch->region->region}} region branches">
		{{$branch->region->region}}
		</a>

	</td>
	<td>

	@foreach ($branch->relatedPeople(9)->get() as $person)
		{{$person->postName()}}<br />

	@endforeach
	</td>
	<td>
		<a href="{{route('branches.show',$branch->id)}}" 
		title="See details of {{$branch->branchname}} branch">
		{{count($branch->servicedBy)}}
		</a>
	</td>
	@if(Auth::user()->hasRole('Admin'))
		<td>
            @include('partials/_modal')
    
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
    @endif

    </tr>
   @endforeach
    
	</tbody>
</table>





@include('partials/_scripts')
    
@stop
