@extends('site/layouts/default')
@section('content')
<h1>{{$region->region}} Region Branches</h1>

<h4> <a href="{{ route('branches.index') }}">Show all branches</a></h4>	
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

	@if(auth()->user()->hasRole('admin'))

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
		{{$branch->id}}
	</td>
	<td>
	@if($branch->servicelines)
		@foreach($branch->servicelines as $serviceline)
				<a href = "{{route('serviceline.show',$serviceline->id)}}" 
				title =" See all {{$serviceline->ServiceLine}} branches">
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
		{{$branch->state}}
	</td>
	<td>


		<a href="{{route('region.show',$branch->region_id)}}" 
		title="See all {{$branch->region->region}} branches">
		{{$branch->region->region}}
		</a>

	</td>

	<td>

	@if($branch->manager->count()>0)
		@foreach ($branch->manager as $manager)
		<a href="{{route('managed.branch',$manager->id)}}"" 
		title="See all branches managed by {{$manager->fullName() }}" >
		{{$manager->fullName() }}
		</a>
		@endforeach
	@endif
		
	</td>
	<td>
	@if(null!==$branch->servicedBy)
		<a href="{{route('branches.show',$branch->id)}}" 
		title="See details of {{$branch->branchname}} branch">
		{{$branch->servicedBy->count()}}
		</a>
	@endif
	</td>

	@if(auth()->user()->hasRole('admin'))

		<td>
            @include('partials/_modal')
    
            <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
				<a class="dropdown-item"
				 href="{{route('branches.edit',$branch->id)}}">
				 <i class="far fa-edit text-info"" 
				 aria-hidden="true"> </i>
				 Edit {{$branch->branchname}} Branch</a>
				 <a class="dropdown-item"
				  data-href="{{route('branches.destroy',$branch->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = "{{$branch->branchname}} branch" href="#"><i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> Delete {{$branch->branchname}} branch</a>

			  </ul>
			</div>
	
    	</td>
    @endif

    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials/_scripts')
@endsection
