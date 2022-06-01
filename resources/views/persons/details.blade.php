@extends('admin.layouts.default')
@section('content')
<div class="container">

	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h2 class="panel-title pull-left"><strong>{{$person->fullName()}}</strong></h2>
			@if(!$person->userdetails->oracleMatch)
			<p class="text text-danger">Not validated in Oracle</p>
			@endif
			<a class="btn btn-primary float-right" href="{{route('users.edit',$person->user_id)}}">

				<i class="far fa-edit text-white"></i>

				Edit
			</a>
			<a href="{{route('person.welcome',$person->id)}}"
				title="Sends a welcome message to {{$person->fullName()}} and their manager.  Note the welcome message is sent automatically when a user is created.  Using this link is only necessary if there was some delay in activating the user."><i class="fa-solid fa-paper-plane"></i>Send Welcome Email</a>
		@can('manage_users')
		<a class="btn btn-danger float-right" 
                data-href="{{route('users.destroy',$user->id)}}" 
				data-toggle="modal" 
				data-target="#confirm-delete" 
				data-title = "{{$person->fullName()}}" 
				href="#">
				<i class="far fa-trash-alt text-white" aria-hidden="true"> </i> 
				Delete </a>
		@endcan
		</div>
		@canImpersonate
			
		<a href="{{route('impersonate', $person->user_id)}}" class="btn btn-warning">
			Login As {{$person->fullName()}}
		</a>
		@endCanImpersonate

		<div class="list-group-item">
			<p class="list-group-item-text"><strong>Role Details</strong></p>
			<p><strong>Mapminer:</strong>
			
				@foreach ($user->roles as $role)
					{{$role->display_name}} @if(! $loop->last), @endif
				@endforeach
			</p>
			<div class="panel-heading clearfix">
			<p><strong>Oracle:</strong>
				
					@if($user->oracleMatch)
						<a href="{{route('oracle.show', $user->oracleMatch->id)}}"
							title="See {{$user->person->fullName()}}'s Oracle details"> {{$user->oracleMatch->job_profile}}</a>
					@else
					{{ucwords($user->person->business_title)}}
					@endif
			</p>

		</div>
	</div>
	<div class="list-group">
		<div class="list-group-item">
			<p class="list-group-item-text"><strong>User Details</strong></p>
			<ul style="list-style-type: none;">
				<li>User id: {{$person->userdetails->id}}</li>
				<li>Person id: {{$person->id}}</li>
				<li>Employee id: {{$person->userdetails->employee_id}}</li>
				<li><strong>Servicelines:</strong><ul>
					@foreach ($user->serviceline as $serviceline)
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
						<li>Address:{{$person->fullAddress()}}
						<li>Phone: {{$person->phone}}</li>
						<li>Email: 
							<a href="mailto:{{$person->userdetails->email}}">{{$person->userdetails->email}}</a>
						</li>
						<li>
							
						</li>
					</ul>
				</div>
				<div class="col-sm-8">
					@if(! empty($person->lat))
						@php
						   $latLng= "@". $person->lat.",".$person->lng .",14z";
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
						@if($person->reportsTo->id)
							Reports To:
		
							<a href="{{route('person.details',$person->reportsTo->id)}}">{{$person->reportsTo->fullName()}}</a>
						@else
							{{$person->reportsTo->fullName()}}
						@endif
						@if($person->userdetails->oracleMatch 
							&& $person->userdetails->oracleMatch->oracleManager
							&& $person->userdetails->oracleMatch->oracleManager->mapminerUser
							&& $person->userdetails->oracleMatch->oracleManager->mapminerUser->person->id != $person->reports_to)
						<p>
							<i class="fa-solid fa-user-plus txt-danger"></i>
							<a href="{{route('oracle.reassign',[$person->id, $person->userdetails->oracleMatch->oracleManager->id])}}"
								title="Change {{$person->fullName()}}'s manager to {{$person->userdetails->oracleMatch->oracleManager->mapminerUser->person->fullName()}}">
							{{$user->oracleMatch->manager_name}}</a>
						</p>
							
						@endif
						<li>Team:</li>
						
						@if($person->userdetails->oracleMatch 
							&& $person->userdetails->oracleMatch->teamMembers->count()>0)
							
							@foreach ($person->userdetails->oraclematch->teamMembers as $reports)
								
								<li>
									@if($reports->mapminerUser)
										<i class="far fa-check-circle text-success"title="In Oracle"></i>
										<a href="{{route('person.details',$reports->mapminerUser->person->id)}}">{{$reports->fullName()}}
										</a>
									@endif
									
									
									
									
								</li>
							
							@endforeach
							@if(isset($addToMapminer))

								<a href="{{route('team.manage', $person->user_id)}}" class="btn btn-info">Manage Team</a>
										
							@endif
						@endif
						
						
					</ul>
				</div>
				<div class="col-sm-8">
					@if($person->directReports->count()>0)
						@include('persons.partials._teammap')
						@endif
					</div>
					<div style="clear:both"></div> 
				</div>
			@endcan
				
			@can('manage_branches')

				<div class="list-group-item">
					<div class="list-group-item-text col-sm-6">
						<p><strong>Branches Serviced</strong></p>

					<table id='sorttable' class ='table table-bordered table-striped table-hover'>
						<thead>
							<th>Branch</th>
							<th>Manager(s)</th>

						</thead>
						<tbody>

						@foreach ($branches as $branch)
							<tr>
								<td>
									<a href="{{route('branches.show',$branch->id)}}"
										title="Review {{$branch->branchname}} branch">
										{{$branch->branchname}}
									</a>
								</td>
								<td>
									@foreach ($branch->manager as $manager)
										<a href="{{route('person.details', $manager->id)}}"
											title="See {{$manager->fullName()}}'s profile">
											{{$manager->fullName()}}
										</a>
										@if (! $loop->last), @endif
									@endforeach
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
					
				</div>
				<div class="col-sm-8">
					@include('persons.partials._branchmap')
				</div>
				<div style="clear:both"></div>  
				</div>
			@endcan
			@if($user->scheduledReports()->exists())
				<div class="list-group-item"><p class="list-group-item-text"><strong>Scheduled Reports</strong>
					<ul style="list-style-type: none;">
						@foreach($user->scheduledReports as $report)
							<li>
								<a href="{{route('reports.show', $report->id)}}"
									title="Review the {{$report->report}} report">
									{{$report->report}}
								</a>
							</li>
						@endforeach
					</ul>
				</div>
			
			@endif
			@can('manage_accounts')
				<div class="list-group-item"><p class="list-group-item-text">
					<strong>Accounts Managed</strong></p>
					<ul style="list-style-type: none;">
						@foreach($person->managesAccount as $account)
							<li><a href="{{route('company.show',$account->id)}}">{{$account->companyname}}</a></li>
						@endforeach
					</ul>
				</div>
			@endcan
				<div class="list-group-item"><p class="list-group-item-text"><strong>Activity</strong></p>
					
					<ul style="list-style-type: none;">
						@if($person->directReports->count()>0)
						<div class="float-right">
						<a href="{{route('team.show',$person->id)}}" class="btn btn-info">	See Teams Mapminer Usage</a>
						</div>
						@endif
						<li>Mapminer User since: {{$user->created_at ? $user->created_at->format('Y-m-d') : ''}}</li>
						<li>Total Logins: {{$user->usage_count}}</li>
						<li>Last Login:
							
							@if($user->lastLogin)
							{{$user->lastLogin->lastactivity->format('Y-m-d')}}
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
