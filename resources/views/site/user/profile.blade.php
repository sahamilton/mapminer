@extends('site.layouts.default')

@section('content')

	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading clearfix">
				<h2 class="panel-title pull-left"><strong>{{$user->person->fullName()}}</strong></h2>
				@if(auth()->user()->canImpersonate() && $user->canBeImpersonated())
					  <a href="{{route('impersonate', $user->id)}}" title="Login as {{$user->person->completeName}}" >
					      <i class=" text-danger fa-duotone fa-key"></i>
					  </a>
					    
				@endif
				<h4 class="panel-title pull-left">
					<strong>
						{{
							$user->oracleMatch ? $user->oracleMatch->job_profile : ucwords($user->person->business_title)
						}}
					</strong>
				</h4>
			</div>
			<div class="col-sm-3 panel-heading float-right">
				@include('persons.partials._avatar')
			</div>
			@if (session()->has('impersonated_by'))
				<p>
					<a href="{{route('dashboard.reset')}}">Reset Sessions</a>
				</p>
				<a href="{{route('impersonate.leave')}}" 
				class="btn btn-success">
					Return to original user
				</a>

			@endif
			<div class="panel-heading clearfix">
				

			</div>
			
			@include('persons.partials._newprofile')
		</div>
	</div>
@endsection
