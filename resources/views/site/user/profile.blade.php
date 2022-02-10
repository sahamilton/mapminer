@extends('site.layouts.default')

@section('content')

<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h2 class="panel-title pull-left"><strong>{{$user->person->fullName()}}</strong></h2>
			
		</div>
		<div class="col-sm-3 panel-heading float-right">
			@include('persons.partials._avatar')
		</div>
		@if (session()->has('impersonated_by'))
		<p>
			<a href="{{route('dashboard.reset')}}">Reset Sessions</a></p>
			<a href="{{route('impersonate.leave')}}" 
			class="btn btn-success">
				Return to original user
			</a>

		@endif
		<div class="panel-heading clearfix">
			<h4 class="panel-title pull-left"><strong>{{ucwords($user->person->business_title)}}</strong></h4>

		</div>
		
		<div class="list-group-item">
			<p class="list-group-item-text"><strong>Role Details</strong></p>
			<ul style="list-style-type: none;">
			@foreach ($user->roles as $role)
				<li>{{$role->display_name}}</li>
			@endforeach
			</ul>
		</div>
	<div class="list-group">
		<div class="list-group-item float-left">
			<p class="list-group-item-text"><strong>User Details</strong></p>
			<ul style="list-style-type: none;">
				<li>User id: {{$user->person->userdetails->id}}</li>
				<li>Person id: {{$user->person->id}}</li>
				<li>Employee id: {{$user->person->userdetails->employee_id}}</li>

				<li><strong>Servicelines:</strong>
					<ul>
						@foreach ($user->person->userdetails->serviceline as $serviceline)
							<li>{{$serviceline->ServiceLine}}</li>
						@endforeach
					</ul>
				</li>
			</ul>
			
		</div>
		<div class="list-group">
    		<div class="list-group-item">
	    		<div class="row">	
					<div class="col-sm-4">
						<p><strong>Contact Details</strong></p>
							<ul style="list-style-type: none;">
								<li>Address:{{$user->person->fullAddress()}}
								<li>Phone: {{$user->person->phone}}</li>
								<li>Email: 
									<a href="mailto:{{$user->person->userdetails->email}}">{{$user->person->userdetails->email}}</a>
								</li>
								
							</ul>
						<a class="btn btn-primary float-right" href="">
						<i class="far fa-edit text-info"></i>
						Edit</a>				
					</div>
					<div class="col-sm-offset-8">
						@if(! empty($user->person->lat))
							@php
							   $latLng= "@". $user->person->lat.",".$user->person->lng .",14z";
							@endphp
					
							 @include('site.user._map')
									
						@else
						<p class="text-danger"><strong>No address or unable to geocode this address</strong></p>		
						@endif
					</div>
					<div style="clear:both"></div> 
				</div>
				
			</div>
			@if(isset($branches))

			<div class="list-group-item">
				<p><strong>Closest Branches to your location</strong></p>
				<div class="row">
					<div class="list-group-item-text col-sm-12">
						@include('branches.partials._nearby')
					</div>
				</div>
			</div>

			@endif
			@if(isset($user->person->reportsTo->id))
				<div class="list-group-item">
					<div class="row">

					<div class="list-group-item-text col-sm-4">
						<p><strong>Reporting Structure</strong></p>
						<ul style="list-style-type: none;">
						@if($user->person->reportsTo)
							<li>Reports To:
							@if($user->person->reportsTo()->count() >0)
							
							<a href="{{route('salesorg.show',$user->person->reportsTo->id)}}">
								{{$user->person->reportsTo->fullName()}}
							</a>
							@else
								No Manager
							@endif
						@endif
						@if($user->person->directReports->count()>0)
							<li>Team:</li>
							@foreach ($user->person->directReports as $reports)
						
								<li>
									<a href="{{route('person.details',$reports->id)}}">
										@if(! $reports->userdetails->oracleMatch)
											<i class="fas fa-times-circle text-danger"
											title="No match with Oracle"></i>
										@else
											<i class="far fa-check-circle text-success"
											title="Matched to Oracle"></i>
										@endif

										{{$reports->fullName()}}
									</a>
								</li>
							
							@endforeach
							@if(isset($addToMapminer))
								@foreach($addToMapminer as $teammember)
									<li>{{$teammember->fullName()}}</li>
								@endforeach
							@endif
						

					@endif

					</ul>
				</div>
				<div class="col-sm-8">
					@if(isset($user->person->directReports->id))
						@include('site.user._teammap')
						@endif
					</div>
					<div style="clear:both"></div> 
				</div>

			</div>
			@endif
				
			
				<div class="list-group-item">
					<div class="row">
					<div class="list-group-item-text col-sm-4">
						<p><strong>Branches Serviced</strong></p>
						@if($user->person->branchesServiced->count()==0)
						<div class="alert alert-warning">
							<p>{{$user->person->firstname}} is not assigned to any branches</p>
						</div>
						@else

					<ul style="list-style-type: none;">
						@foreach ($user->person->branchesServiced as $branch)
							<li><a href="{{route('branches.show',$branch->id)}}">{{$branch->branchname}}</a> </li>
						@endforeach
					</ul>

					@endif
					@if($user->id == auth()->user()->id)
					<div class="alert alert-warning">
					<p class="">If your branch associations are incorrect or incomplete you should contact <a href="mailto: {{config('mapminer.system_contact')}}">
							<i class="far fa-envelope" aria-hidden="true"> </i>
							 {{config('mapminer.system_contact')}}
						</a>.</p> 
					</div>
					@endif
				</div>
				<div class="col-sm-8">
					@include('site.user._branchmap')
				</div>
				<div style="clear:both"></div>  
				</div>
				
						
							 
						
							
				
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
			@if($user->person->managesAccount()->exists())
				<div class="list-group-item"><p class="list-group-item-text"><strong>Accounts Managed</strong>
					<ul style="list-style-type: none;">
						@foreach($user->person->managesAccount as $account)
							<li><a href="{{route('company.show',$account->id)}}">{{$account->companyname}}</a></li>
						@endforeach
					</ul>
				</div>
			@endif

			
				<div class="list-group-item"><p class="list-group-item-text"><strong>Industry Focus</strong>
				
					@if(count($user->person->industryfocus)==0)
					<div class="alert alert-warning">
						<p>{{$user->person->firstname}} is not associated with any particular industry.</p>
					@else
					<ul style="list-style-type: none;">
						@foreach($user->person->industryfocus as $industry)
							<li>{{$industry->filter}}</a></li>
						@endforeach
					</ul>
					@endif
					@if($user->id == auth()->user()->id)
					<a href="{{route('industryfocus.index')}}" class="btn btn-primary">
						<i class="far fa-edit text-info"></i> Change</a>
					@endif
				</div>
			
				<div class="list-group-item">
					<p class="list-group-item-text"><strong>Activity</strong></p>
					<ul style="list-style-type: none;">
						@if($user->person->directReports->count()>0)
						<div class="float-right">
						<a href="{{route('team.show',$user->person->id)}}" class="btn btn-info">	See Teams Mapminer Usage</a>
						</div>
						@endif
						<li>Last Login: {{$user->lastlogin ? $user->lastlogin->format('M d, Y') : 'Never logged in'}}</li>
						<li>Total Logins: {{$user->usage_count}}</li>
						

						
					</li>
							
					</ul>
				</div>
				@if ($user->id == auth()->user()->id)
				<div class="alert alert-warning">
					<p class="list-group-item-text"><strong>Corrections</strong></p>
					<ul style="list-style-type: none;">
						<p class="">If any details of your profile are incorrect or incomplete please contact <a href="mailto: {{config('mapminer.system_contact')}}">
							<i class="far fa-envelope" aria-hidden="true"> </i>
							 {{config('mapminer.system_contact')}}
						</a>.</p>
							
					</ul>
				</div>
				@endif

			</div>
		</div>
	</div>
</div>
@endsection
