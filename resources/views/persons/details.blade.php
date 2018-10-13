@extends('admin.layouts.default')
@section('content')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h2 class="panel-title pull-left"><strong>{{$people->postName()}}</strong></h2>
			<a class="btn btn-primary pull-right" href="{{route('users.edit',$people->user_id)}}">

				<i class="far fa-edit text-info""></i>

				Edit
			</a>
		</div>
		<div class="list-group-item">
			<p class="list-group-item-text"><strong>Role Details</strong></p>
			<ul style="list-style-type: none;">
			@foreach ($people->userdetails->roles as $role)
				<li>{{$role->name}}</li>
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
			@if($people->reportsTo || $people->directReports->count()>0)
				<div class="list-group-item">
					<div class="list-group-item-text col-sm-4">
						<p><strong>Reporting Structure</strong></p>
						<ul style="list-style-type: none;">
						@if($people->reportsTo)
							<li>Reports To:
							<a href="{{route('person.details',$people->reportsTo->id)}}">{{$people->reportsTo->postName()}}</a></li>
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
			@endif
				
			@if($people->branchesServiced->count()>0)

				<div class="list-group-item">
					<div class="list-group-item-text col-sm-4">
						<p><strong>Branches Serviced</strong></p>

					<ul style="list-style-type: none;">
						@foreach ($people->branchesServiced as $branch)
							<li><a href="{{route('branches.show',$branch->id)}}">{{$branch->branchname}}</a></li>
						@endforeach
					</ul>
				</div>
				<div class="col-sm-8">
					@include('persons.partials._branchmap')
				</div>
				<div style="clear:both"></div>  
				</div>
			@endif
			@if($people->managesAccount->count()>0)
				<div class="list-group-item"><p class="list-group-item-text">Accounts Managed</p>
					<ul style="list-style-type: none;">
						@foreach($people->managesAccount as $account)
							<li><a href="{{route('company.show',$account->id)}}">{{$account->companyname}}</a></li>
						@endforeach
					</ul>
				</div>
			@endif
				<div class="list-group-item"><p class="list-group-item-text"><strong>Activity</strong></p>
					<ul style="list-style-type: none;">
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
@endsection