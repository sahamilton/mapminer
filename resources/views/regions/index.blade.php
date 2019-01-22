@extends('admin.layouts.default')
@section('content')
<h2>Regions</h2>
<a href="{{route('region.create')}}" class="btn btn-info float-right">Create Region</a>

    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>

	<thead>
		<th>Region</th>
		<th>Branches</th>
		<th>Actions</th>
	</thead>
	<tbody>
		@foreach ($regions as $region)
		<tr>
			
			<td>{{$region->region}}</td>
			<td>{{$region->branches_count}}</td>
			<td> 
				<div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
			
				<a class="dropdown-item"
					href="{{route('region.edit',$region->id)}}"><i class="far fa-edit text-info"" aria-hidden="true"> </i>Edit {{$region->region}} Region
				</a>
				<a class="dropdown-item"
				   data-href="{{route('region.destroy',$region->id)}}" data-toggle="modal" 
				   data-target="#confirm-delete" 
				   data-title = "{{$region->region}} region" 
				   href="#"><i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> Delete {{$region->region}} region
				</a>
			  </ul>
			</div>
		</td>
			
		</tr>
		@endforeach
	</tbody>
</table>
@include('partials._modal')
@include('partials._scripts')
@endsection