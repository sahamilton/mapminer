@extends('admin.layouts.default')
@section('content')

<h1>All How To Fields</h1>

<div class="pull-right">
<a href="{{{ route('howtofields.create') }}}" class="btn btn-small btn-info iframe">
<i class="fa fa-plus-circle text-success" aria-hidden="true"></i>
 Create New Field</a>
</div>

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Field</th>
		<th>Reqd</th>
		<th>Type</th>
		<th>Values</th>
		<th>Group</th>
		<th>Actions</th>

	</thead>
	<tbody>
	@foreach($howtofields as $howtofield)
		<tr>  
		<td>{{$howtofield->fieldname}}</td>
		<td>
		@if($howtofield->required==0)
			Yes
		@else
			No
		@endif
		</td>
		<td>{{$howtofield->type}}</td>
		<td>{{$howtofield->values}}</td>
		<td>{{$howtofield->group}}</td>
		<td>
			<div class="btn-group">
				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu" role="menu">

					<li>
						<a href="{{route('howtofields.edit', $howtofield->id)}}">
						<i class="fa fa-pencil" aria-hidden="true"></i>
						Edit  {{$howtofield->fieldname}}
						</a>
					</li>
					<li>
						<a data-href="{{route('howtofields.destroy',$howtofield->id)}}" 
						data-toggle="modal" 
						data-target="#confirm-delete" 
						data-title = "location" 
						href="#">
						<i class="fa fa-trash-o" aria-hidden="true"> </i> 
						Delete {{$howtofield->fieldname}}
						</a>
					</li>

				</ul>
			</div>	
		</td>
		</tr>
	@endforeach

	</tbody>
</table>
@include('partials._modal')
@include('partials/_scripts')
@stop