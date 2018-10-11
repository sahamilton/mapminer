@extends('site.layouts.default')
@section('content')

<div class="jumbotron">
	<div class="container" style="position:relative;text-align:center">
		<h4 ">Welcome to the My PeopleReady&reg; National Account Locator</h4>
		<div id="welcome">
<<<<<<< HEAD
			@if(Auth::check()) 
=======
			@if(auth()->check()) 
>>>>>>> development
				<div id="accountbtn" style="text-align:left; padding-left:20%;padding-top:100px">
					<a href="" class="btn btn-lg btn-primary" 
					title='Search for specific accounts'>Account Views</a>
				</div>

				<div id="branchbtn" style="text-align:left; padding-left:70%;padding-top:50px">
					<a href="{{route('branches.index')}}" class='btn btn-lg btn-warning' 
					title='Explore Branches and their national account locations'>Branch Views</a>
				</div>

				<div id="peoplebtn" style="text-align:left; padding-left:50%;padding-top:100px">
					<a href="{{route('person.index')}}" class='btn btn-lg btn-success' 
					title='Search for people'>People Views</a>
				</div>
				
				<div id="projectbtn" style="text-align:left; padding-left:70%;padding-top:100px">
					<a href="" class="btn btn-lg btn-primary" 
					title='Search for specific construction projects'>Construction Project Views</a>
				</div>

			@else
				<div id="loginbtn" style="padding-left:0px;padding-top:200px">
					<a href='login'class='btn btn-lg btn-success'>Login</a>
				</div>
			@endif
		</div>
	</div>
</div>

@endsection
