@extends('admin.layouts.default')
@section('content')
<div class="container">
	<h2>{{$activityType->activity}} Activity</h2>
	<p><a href="{{route('activitytype.index')}}">Return to all Activity Types</a></p>
	<div class="float-right">
		<a href="{{route('activitytype.edit',$activityType->id)}}" class="btn btn-info">Edit {{$activityType->activity}} Activity Type</a>
	</div>
	<div class="row">
		<h4>People Using {{$activityType->activity}} Activity Type</h4>
		<table id='sorttable5' class ='table table-bordered table-striped table-hover'>
			<thead>
				<th>Person</th>
				<th>Role</th>
				<th>Count</th>
				<th>Last {{$activityType->activity}} Activity</th>
				<th>Date</th>
			</thead>
			<tbody>
				@foreach ($people as $person)

				<tr>
					<td>{{$person->fullName}}</td>
					<td>
						@foreach ($person->userdetails->roles as $role)
						<li>{{$role->display_name}}</li>
						@endforeach
					</td>
					<td>{{$person->activities->count()}}</td>
					<td>{{$person->activities->last()->note}}</td>
					<td>{{$person->activities->last()->activity_date->format('Y-m-d')}}</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

@include('partials._scripts')
@endsection