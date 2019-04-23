@extends('admin.layouts.default')
@section('content')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h2 class="panel-title pull-left"><strong>{{$people->fullName()}}</strong></h2>
			<a class="btn btn-primary float-right" href="{{route('users.edit',$people->user_id)}}">

				<i class="far fa-edit text-white"></i>

				Edit
			</a>
		
		@can('manage_users')
		<a class="btn btn-danger float-right" 
                data-href="{{route('users.destroy',$people->user_id)}}" 
				data-toggle="modal" 
				data-target="#confirm-delete" 
				data-title = "{{$people->fullName()}}" 
				href="#">
				<i class="far fa-trash-alt text-white" aria-hidden="true"> </i> 
				Delete </a>
		@endcan
		</div>
		@canImpersonate
			
		<a href="{{route('impersonate', $people->user_id)}}" class="btn btn-warning">
			Login As {{$people->fullName()}}
		</a>
		@endCanImpersonate
		<div class="list-group-item">
			<p class="list-group-item-text"><strong>Role Details</strong></p>
			<ul style="list-style-type: none;">
			@foreach ($people->userdetails->roles as $role)
				<li>{{$role->display_name}}</li>
			@endforeach
			</ul>
		</div>
	<div class="list-group">
		<div class="list-group-item">
			<p class="list-group-item-text"><strong>User Details</strong></p>
			<ul style="list-style-type: none;">
				<li>User id: {{$people->userdetails->id}}</li>
				<li>Person id: {{$people->id}}</li>
				<li>Employee id: {{$people->userdetails->employee_id}}</li>
				<li><strong>Servicelines:</strong><ul>
					@foreach ($people->userdetails->serviceline as $serviceline)
						<li>{{$serviceline->ServiceLine}}</li>
					@endforeach
				</ul>
			</li>
		</ul>
		</div>
		<div class="list-group">
			<div class="list-group-item">
				<div class="list-group-item-text col-sm-4">
					<p><strong>Contact Details</strong></p>
						<ul style="list-style-type: none;">
						<li>Address:{{$people->fullAddress()}}
						<li>Phone: {{$people->phone}}</li>
						<li>Email: 
							<a href="mailto:{{$people->userdetails->email}}">{{$people->userdetails->email}}</a>
						</li>
						<li>
							
						</li>
					</ul>
				</div>
				<div class="col-sm-8">
					@if(! empty($people->lat))
						@php
						   $latLng= "@". $people->lat.",".$people->lng .",14z";
						@endphp
				
						 @include('persons.partials._map')
								
					@else
					<p class="text-danger"><strong>No address or unable to geocode this address</strong></p>		
					@endif
				</div>
				<div style="clear:both"></div> 
			</div>
			@can('manage_people')
				<div class="list-group-item">
					<div class="list-group-item-text col-sm-4">
						<p><strong>Reporting Structure</strong></p>
						<ul style="list-style-type: none;">
						@if($people->reportsTo)
							<li>Reports To:
							<a href="{{route('person.details',$people->reportsTo->id)}}">{{$people->reportsTo->fullName()}}</a></li>
						@endif
						@if($people->directReports->count()>0)
							<li>Team:</li>
							@foreach ($people->directReports as $reports)
						
								<li><a href="{{route('person.details',$reports->id)}}">{{$reports->fullName()}}</a></li>
							
							@endforeach
						
						

					@endif

					</ul>
				</div>
				<div class="col-sm-8">
					@if($people->directReports->count()>0)
						@include('persons.partials._teammap')
						@endif
					</div>
					<div style="clear:both"></div> 
				</div>
			@endcan
				
			@can('manage_branches')

				<div class="list-group-item">
					<div class="list-group-item-text col-sm-4">
						<p><strong>Branches Serviced</strong></p>

					<ul style="list-style-type: none;">
						@foreach ($people->branchesServiced as $branch)
							<li><a href="{{route('branches.show',$branch->id)}}">{{$branch->branchname}}</a></li>
						@endforeach
					</ul>
					<p><a href="{{route('branchassignments.show',$people->user_id)}}" class="btn btn-info">Update Branch Assignments</a></p>
				</div>
				<div class="col-sm-8">
					@include('persons.partials._branchmap')
				</div>
				<div style="clear:both"></div>  
				</div>
			@endcan
			@can('manage_accounts')
				<div class="list-group-item"><p class="list-group-item-text">Accounts Managed</p>
					<ul style="list-style-type: none;">
						@foreach($people->managesAccount as $account)
							<li><a href="{{route('company.show',$account->id)}}">{{$account->companyname}}</a></li>
						@endforeach
					</ul>
				</div>
			@endcan
				<div class="list-group-item"><p class="list-group-item-text"><strong>Activity</strong></p>
					<ul style="list-style-type: none;">
						@if($people->directReports->count()>0)
						<div class="float-right">
						<a href="{{route('team.show',$people->id)}}" class="btn btn-info">	See Teams Mapminer Usage</a>
						</div>
						@endif
						<li>Total Logins: {{$track->count()}}</li>
						<li>Last Login:
							@if($track->count()>0)
							{{$track->first()->lastactivity->format('Y-m-d')}}
						@endif
					</li>
							
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@include('partials._scripts')
@include('partials._modal')
@endsection
