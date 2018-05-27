@extends('admin.layouts.default')
@section('content')
<div class="container">
	<div class="panel panel-default">
		<div class="panel-heading clearfix">
			<h2 class="panel-title pull-left">{{$people->postName()}}</h2>
			<a class="btn btn-primary pull-right" href="#">
				<i class="fa fa-pencil"></i>
				Edit
			</a>
		</div>
		<div class="list-group-item">
				<p class="list-group-item-text">Role Details</p>
				<ul style="list-style-type: none;">
				@foreach ($people->userdetails->roles as $role)
					<li>{{$role->name}}</li>
				@endforeach
				</ul>
			</div>
		<div class="list-group">
			<div class="list-group-item">
				<p class="list-group-item-text">Contact Details</p>
				<ul style="list-style-type: none;">
					<li>
						<strong>Phone:</strong>
						{{$people->phone}}
					</li>
					<li>
						<strong>Address:</strong>
						{{$people->fullAddress()}}
						@if($people->lat)
							@php
							   $latLng= "@". $people->lat.",".$people->lng .",14z";
							@endphp
							<a href="https://www.google.com/maps/{{$latLng}}" target="_blank">
								<i class="fas fa-map-marker-alt"></i></a>
						@endif
					</li>
					<li>
						<strong>Email:</strong>
						<a href="mailto:{{$people->userdetails->email}}">{{$people->userdetails->email}}</a>
					</li>
				</ul>
			</div>


			@if($people->reportsTo || count($people->directReports)>0)
				<div class="list-group-item"><p class="list-group-item-text">Reporting Structure</p>
					<ul style="list-style-type: none;">
					@if($people->reportsTo)
						<li><strong>Reports To:</strong>
						<a href="{{route('person.show',$people->reportsTo->id)}}">{{$people->reportsTo->postName()}}</a></li>
					@endif
					@if(count($people->directReports)>0)
						<li><strong>Team:</strong></li>
						@foreach ($people->directReports as $reports)
							<li><a href="{{route('person.show',$reports->id)}}">{{$reports->fullName()}}</a></li>
						@endforeach
					@endif

					</ul>
				</div>
			@endif
			
			@if(count($people->branchesServiced)>0)
				<div class="list-group-item"><p class="list-group-item-text">Branches Serviced</p>
					<ul style="list-style-type: none;">
					
				
						
						@foreach ($people->branchesServiced as $branch)
							<li><a href="">{{$branch->branchname}}</a></li>
						@endforeach
					

					</ul>
				</div>


			@endif
			@if(count($people->managesAccount)>0)
				<div class="list-group-item"><p class="list-group-item-text">Accounts Managed</p>
					<ul style="list-style-type: none;">
						@foreach($people->managesAccount as $account)
							<li><a href="">{{$account->companyname}}</a></li>
						@endforeach
					</ul>
				</div>
			@endif
			<div class="list-group-item"><p class="list-group-item-text">Activity</p>
					<ul style="list-style-type: none;">
						<li><strong>Total Logins:</strong> {{count($track)}}</li>
						<li><strong>Last Login:</strong> {{$track->first()->lastactivity->format('Y-m-d')}}</li>
							
					</ul>
				</div>

			<div class="panel-footer">
				<small>Built with Bootcards - Base Card</small>
			</div>
		</div>
	</div>
</div>
@endsection