@extends('admin.layouts.default')
@section('content')
<div class="container">
	<h2>Activity Types</h2>
	<div class="float-right">
		<a href="{{route('activitytype.create')}}" class="btn btn-info">Create New Activity Type</a>
	</div>
	<div class="row">
		<table id='sorttable5' class ='table table-bordered table-striped table-hover'>
			<thead>
				<th>Activity</th>
				<th>Count</th>
				<th>Actions</th>
			</thead>
			<tbody>
				@foreach ($activitytypes as $activitytype)
				<tr>
					<td><a href="{{route('activitytype.show',$activitytype->id)}}">{{$activitytype->activity}}</a></td>
					
					<td>{{$activitytype->activities_count}}</td>

					<td>
						<div class="btn-group">
				            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				            <span class="caret"></span>
				            <span class="sr-only">Toggle Dropdown</span>
				            </button>
				            <ul class="dropdown-menu" role="menu">

					            <a class="dropdown-item"
					                 href="{{route('activitytype.edit',$activitytype->id)}}">
					                 <i class="far fa-edit text-info"" aria-hidden="true"> </i>
					                    Edit {{$activitytype->activity}} activity type
					            </a>
								<a class="dropdown-item"
								 	data-href="{{route('activitytype.destroy',$activitytype->id)}}" 
									data-toggle="modal" 
									data-target="#confirm-delete" 
									data-title = " the {{$activitytype->activity}} activity type"
									title ="Delete {{$activitytype->activity}} activity type" 
									href="#">
									<i class="far fa-trash-alt text-danger" aria-hidden="true"> </i> 
									Delete {{$activitytype->activity}} activity type
								</a>
							</ul>
						</div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

@include('partials._modal')
@include('partials._scripts')
@endsection