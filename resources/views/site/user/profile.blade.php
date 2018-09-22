@extends('site.layouts.default')
@section('content')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h2 class="panel-title pull-left"><strong>{{$user->person->postName()}}</strong></h2>
			
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
						<i class="fa fa-pencil"></i>
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

			@if($user->person->reportsTo || $user->person->directReports->count()>0)
				<div class="list-group-item">
					<div class="row">
					<div class="list-group-item-text col-sm-4">
						<p><strong>Reporting Structure</strong></p>
						<ul style="list-style-type: none;">
						@if($user->person->reportsTo)
							<li>Reports To:
							<a href="{{route('person.details',$user->person->reportsTo->id)}}">{{$user->person->reportsTo->postName()}}</a></li>
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
			</div>
			@endif
				
			@can('service_branches')
				<div class="list-group-item">
					<div class="row">
					<div class="list-group-item-text col-sm-4">
						<p><strong>Branches Serviced</strong></p>

					<ul style="list-style-type: none;">
						@foreach ($user->person->branchesServiced as $branch)
							<li><a href="{{route('branches.show',$branch->id)}}">{{$branch->branchname}}</a></li>
						@endforeach
					</ul>
					<a class="btn btn-primary pull-right" href="{{route('branchassignments.index')}}">
						<i class="fa fa-pencil"></i>
						Edit</a>
				</div>
				<div class="col-sm-8">
					@include('site.user._branchmap')
				</div>
				<div style="clear:both"></div>  
				</div>

				
			</div>
			@endcan
			@if($user->person->managesAccount()->exists())
				<div class="list-group-item"><p class="list-group-item-text">Accounts Managed</p>
					<ul style="list-style-type: none;">
						@foreach($user->person->managesAccount as $account)
							<li><a href="{{route('company.show',$account->id)}}">{{$account->companyname}}</a></li>
						@endforeach
					</ul>
				</div>
			@endif
				<div class="list-group-item">
					<p class="list-group-item-text"><strong>Activity</strong></p>
					<ul style="list-style-type: none;">
						<li>Total Logins: {{$user->usage()->count()}}</li>
						<li>First Login:{{$user->usage()->oldest()->first()->lastactivity->format('M d, Y')}}</li>
						<li>Last Login:{{$user->usage()->latest()->first()->lastactivity->format('M d, Y')}}</li
							
							
						
					</li>
							
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection