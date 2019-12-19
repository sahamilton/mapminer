@extends('site.layouts.default')
@section('content')

<div class="jumbotron">
	<div class="container" style="position:relative;text-align:center">
		<h4>Welcome to the My PeopleReady&reg; Mapminer</h4>
		<div id="welcome" style="background-color: white">

			@if(auth()->check()) 

				@include('maps.partials._form)
				
				@include('partials._santa')
			@else
				<div id="loginbtn" style="padding-left:0px;padding-top:200px">
					<a href='login'class='btn btn-lg btn-success'>Login</a>
				</div>
			@endif
		</div>
	</div>
</div>

@endsection
