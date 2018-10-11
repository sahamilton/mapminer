<<<<<<< HEAD
@extends('admin.layouts.default')
=======
@extends('site.layouts.default')
>>>>>>> development
@section('content')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h2 class="panel-title pull-left"><strong>{{$user->person->postName()}}</strong></h2>
<<<<<<< HEAD
			<a class="btn btn-primary pull-right" href="{{route('users.edit',$user->person->user_id)}}">
				<i class="fa fa-pencil"></i>
				Edit
			</a>
=======
			
>>>>>>> development
		</div>
		<div class="list-group-item">
			<p class="list-group-item-text"><strong>Role Details</strong></p>
			<ul style="list-style-type: none;">
			@foreach ($user->person->userdetails->roles as $role)
				<li>{{$role->name}}</li>
			@endforeach
			</ul>
		</div>
	<div class="list-group">
		<div class="list-group-item">
			<p class="list-group-item-text"><strong>User Details</strong></p>
			<ul style="list-style-type: none;">
				<li>User id: {{$user->person->userdetails->id}}</li>
				<li>Person id: {{$user->person->id}}</li>
				<li>Employee id: {{$user->person->userdetails->employee_id}}</li>
<<<<<<< HEAD
				<li><strong>Servicelines:</strong><ul>
					@foreach ($user->person->userdetails->serviceline as $serviceline)
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
						<li>Address:{{$user->person->fullAddress()}}
						<li>Phone: {{$user->person->phone}}</li>
						<li>Email: 
							<a href="mailto:{{$user->person->userdetails->email}}">{{$user->person->userdetails->email}}</a>
						</li>
						<li>
							
						</li>
					</ul>
				</div>
				<div class="col-sm-8">
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
			@if($user->person->reportsTo || $user->person->directReports->count()>0)
				<div class="list-group-item">
=======
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
						<a class="btn btn-primary pull-right" href="">
						<i class="far fa-edit text-info""></i>
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
			@if($user->person->reportsTo || $user->person->directReports->count()>0)
				<div class="list-group-item">
					<div class="row">
>>>>>>> development
					<div class="list-group-item-text col-sm-4">
						<p><strong>Reporting Structure</strong></p>
						<ul style="list-style-type: none;">
						@if($user->person->reportsTo)
							<li>Reports To:
<<<<<<< HEAD
							<a href="{{route('person.details',$user->person->reportsTo->id)}}">{{$user->person->reportsTo->postName()}}</a></li>
=======
							<a href="{{route('salesorg',$user->person->reportsTo->id)}}">{{$user->person->reportsTo->postName()}}</a></li>
>>>>>>> development
						@endif
						@if($user->person->directReports->count()>0)
							<li>Team:</li>
							@foreach ($user->person->directReports as $reports)
						
								<li><a href="{{route('person.details',$reports->id)}}">{{$reports->fullName()}}</a></li>
							
							@endforeach
						
						

					@endif

					</ul>
				</div>
				<div class="col-sm-8">
					@if($user->person->directReports->count()>0)
						@include('site.user._teammap')
						@endif
					</div>
					<div style="clear:both"></div> 
				</div>
<<<<<<< HEAD
			@endif
				
			@if($user->person->branchesServiced()->exists())

				<div class="list-group-item">
					<div class="list-group-item-text col-sm-4">
						<p><strong>Branches Serviced</strong></p>

=======
			</div>
			@endif
				
			@can('service_branches')
				<div class="list-group-item">
					<div class="row">
					<div class="list-group-item-text col-sm-4">
						<p><strong>Branches Serviced</strong></p>
						@if($user->person->branchesServiced->count()==0)
						<div class="alert alert-warning">
							<p>You are not assigned to any branches</p>
						</div>
						@else
>>>>>>> development
					<ul style="list-style-type: none;">
						@foreach ($user->person->branchesServiced as $branch)
							<li><a href="{{route('branches.show',$branch->id)}}">{{$branch->branchname}}</a></li>
						@endforeach
					</ul>
<<<<<<< HEAD
=======
					@endif
					<a class="btn btn-primary pull-right" href="{{route('branchassignments.index')}}">
						<i class="far fa-edit text-info"></i>
						Change Assignments</a>
>>>>>>> development
				</div>
				<div class="col-sm-8">
					@include('site.user._branchmap')
				</div>
				<div style="clear:both"></div>  
				</div>
<<<<<<< HEAD
			@endif
=======

				
			</div>
			@endcan
>>>>>>> development
			@if($user->person->managesAccount()->exists())
				<div class="list-group-item"><p class="list-group-item-text">Accounts Managed</p>
					<ul style="list-style-type: none;">
						@foreach($user->person->managesAccount as $account)
							<li><a href="{{route('company.show',$account->id)}}">{{$account->companyname}}</a></li>
						@endforeach
					</ul>
				</div>
			@endif
<<<<<<< HEAD
				<div class="list-group-item"><p class="list-group-item-text"><strong>Activity</strong></p>
					<ul style="list-style-type: none;">
						<li>Total Logins: {{$user->usage->count()}}</li>
						<li>Last Login:
							
							
=======
			
				<div class="list-group-item"><p class="list-group-item-text"><strong>Industry Focus</strong>
				</p>
					@if(count($user->person->industryfocus)==0)
					<div class="alert alert-warning">
						<p>You are not associated with any particular industy.</p>
					@else
					<ul style="list-style-type: none;">
						@foreach($user->person->industryfocus as $industry)
							<li>{{$industry->filter}}</a></li>
						@endforeach
					</ul>
					@endif
					<a href="{{route('industryfocus.index')}}"" class="btn btn-primary">
						<i class="far fa-edit text-info"></i> Change</a>
				</div>
			
				<div class="list-group-item">
					<p class="list-group-item-text"><strong>Activity</strong></p>
					<ul style="list-style-type: none;">
						
						<li>Total Logins: {{$user->usage()->count()}}</li>
						@if($user->usage()->oldest()->first()->lastactivity)
						<li>First Login:{{$user->usage()->oldest()->first()->lastactivity->format('M d, Y')}}</li>
						<li>Last Login:{{$user->usage()->latest()->first()->lastactivity->format('M d, Y')}}
						@endif	
>>>>>>> development
						
					</li>
							
					</ul>
				</div>
<<<<<<< HEAD
=======
				<div class="alert alert-warning">
					<p class="list-group-item-text"><strong>Corrections</strong></p>
					<ul style="list-style-type: none;">
						<p class="">If any details of your profile are incorrect or incomplete please contact <a href="mailto:salesops@trueblue.com">
							<i class="far fa-envelope" aria-hidden="true"> </i>
							 salesops@trueblue.com
						</a>.</p>
							
					</ul>
				</div>
>>>>>>> development
			</div>
		</div>
	</div>
</div>
@endsection