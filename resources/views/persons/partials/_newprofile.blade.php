<div class="list-group-item">
	<p class="list-group-item-text"><strong>Role Details</strong></p>
	<p><strong>Mapminer:</strong>
	
		@foreach ($user->roles as $role)
			{{$role->display_name}} @if(! $loop->last), @endif
		@endforeach
	</p>
	@can('manage.people')
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
	@endcan
</div>
<div class="list-group">
	<div class="list-group-item">
		<p class="list-group-item-text"><strong>User Details</strong></p>
		<ul style="list-style-type: none;">
			<li>User id: {{$user->id}}</li>
			<li>Person id: {{$user->person->id}}</li>
			<li>Employee id: {{$user->employee_id}}</li>
			<li>
				<strong>Servicelines:</strong>
				<ul>
					@foreach ($user->serviceline as $serviceline)
						<li>{{$serviceline->ServiceLine}}</li>
					@endforeach
				</ul>
			</li>
		</ul>
	</div>
	<div class="list-group">
		<div class="list-group-item">
			<div class="row">
				<div class="list-group-item-text col-sm-4">
					<p><strong>Contact Details</strong></p>
					<ul style="list-style-type: none;">
						<li>Address:{{$user->person->fullAddress()}}</li>
						<li>Phone: {{$user->person->phone}}</li>
						<li>Email: 
							<a href="mailto:{{$user->email}}">{{$user->email}}</a>
						</li>
					</ul>
					@if($user->id === auth()->user()->id)
						<button class="btn btn-primary float-right" 
						data-toggle="modal" 
						data-target="#modalUpdateProfile">
						<i class="far fa-edit text-info" aria-hidden="true"></i>
						Edit</button>
						@include('site.user._modalupdateprofile')
					@endif
					
				</div>
				<div class="col-sm-8">
					@if(! empty($user->person->lat))
						@php
						   $latLng= "@". $user->person->lat.",".$user->person->lng .",14z";
						@endphp
				
						 @include('persons.partials._map')
								
					@else
					<p class="text-danger"><strong>No address or unable to geocode this address</strong></p>		
					@endif
				</div>
			</div>
			<div style="clear:both"></div> 
		</div>
	</div>
	<div class="list-group">
		<div class="list-group-item">
		@can('manage_people')
			
				<livewire:manage-team :user='$user' />
			
			
		@endcan
	</div>
	<div class="list-group">
		<div class="list-group-item">
				<div class="row">
					<div class="list-group-item-text col-sm-4">
						<p><strong>Branches Serviced</strong></p>
						@if(! $branchesServiced)
							<div class="alert alert-warning">
								<p>{{$user->person->firstname}} is not assigned to any branches</p>
							</div>
							@else

						<ul style="list-style-type: none;">
							@foreach ($branchesServiced as $branch)
								<li><a href="{{route('branches.show',$branch->id)}}">{{$branch->branchname}}</a> </li>
							@endforeach
						</ul>

					@endif
				</div>
				<div class="float-right col-sm-8">
					@include('persons.partials._branchmap')
				</div>
				<div style="clear:both"></div>
				@if($user->id === auth()->user()->id)
					@if(auth()->user()->hasRole(['market_manager']))
						<a href="{{route('branchassignments.show', auth()->user()->id)}}"
						title="Update your branch assignments"
						class="btn btn-info" >Update Your branch Associations</a>
					@else
					<div class="alert alert-warning">
					<p class="">If your branch associations are incorrect or incomplete you should contact <a href="mailto: {{config('mapminer.system_contact')}}">
							<i class="far fa-envelope" aria-hidden="true"> </i>
							 {{config('mapminer.system_contact')}}
						</a>.</p> 
					</div>
					@endif
				@endif
			</div>
			
	</div>
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
	@if($user->hasRole(['national_account_manager']))

		<div class="list-group-item"><p class="list-group-item-text">
			<strong>Accounts Managed</strong></p>
			<ul style="list-style-type: none;">
				@foreach($user->person->managesAccount as $account)
					<li><a href="{{route('company.show',$account->id)}}">{{$account->companyname}}</a></li>
				@endforeach
			</ul>
		</div>
	@endcan
	<div class="list-group-item"><p class="list-group-item-text"><strong>Activity</strong></p>
		
		<ul style="list-style-type: none;">
			@if($user->person->directReports->count()>0)
				<div class="float-right">
					<a href="{{route('team.show',$user->person->id)}}" class="btn btn-info">	See Teams Mapminer Usage</a>
				</div>
			@endif
			<table class="table col-sm-8">
				<tbody>
					<tr>
						<td>Mapminer User since: </td><td>{{$user->created_at ? $user->created_at->format('Y-m-d') : ''}}</td>
					</tr>
					<tr>
						<td>Total Logins: </td><td>{{$user->usage_count}}</td>
						</tr>
					<tr>
						<td>Last Login:</td>
						<td>
							@ray($user)
							@if($user->lastlogin)
								{{$user->lastlogin->format('Y-m-d')}}
							@endif
						</td>
					</tr>

				</tbody>
			</table>
		</li>
				
		</ul>
	</div>
	@if ($user->id === auth()->user()->id)
		<div class="alert alert-warning">
			<p class="list-group-item-text"><strong>Corrections</strong></p>
			<ul style="list-style-type: none;">
				<p class="">If any details of your profile are incorrect or incomplete please contact <a href="mailto: {{config('mapminer.system_contact')}}">
					<i class="far fa-envelope" aria-hidden="true"> </i>
					 {{config('mapminer.system_contact')}}
				</a>.</p>
					
			</ul>
		</div>
		@include('partials._scripts')

		@include('partials._modal')
	@endif
</div>
