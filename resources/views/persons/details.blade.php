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
			@if(! $user->lastLogin)
			<a href="{{route('person.welcome',$person->id)}}"
				title="Sends a welcome message to {{$person->fullName()}} and their manager.  Note the welcome message is sent automatically when a user is created.  Using this link is only necessary if there was some delay in activating the user."><i class="fa-solid fa-paper-plane"></i>Send Welcome Email</a>
			@endif
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

		@include('persons.partials._newprofile')
	</div>
</div>
@include('partials._scripts')
@include('partials._modal')
@endsection
