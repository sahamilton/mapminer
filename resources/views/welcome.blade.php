@extends('site.layouts.default')
@section('content')
@include('partials._santa')

@if(!auth()->check())

<img class="santa" src="/assets/img/santa.png" width="20%" />
	<div class="jumbotron" style="margin-top:30px">
		<div class="container" style="position:relative;text-align:center">
			<h4 ">Welcome to the PeopleReady&reg; Mapminer</h4>
			<div id="welcome">
				<div id="loginbtn" style="padding-left:0px;padding-top:200px">
				
					<a href='login'class='btn btn-lg btn-success'>Login</a>


				</div>
			</div>
		</div>
	</div>
@else
	<div class="jumbotron" style="margin-top:30px">
		<div class="container" style="position:relative;text-align:center">
			<h4 ">Welcome, {{auth()->user()->person()->first()->firstname}} to the PeopleReady&reg; Mapminer</h4>
			<div id="welcome">
				<div id="accountbtn" style="text-align:left; padding-left:20%;padding-top:100px">
					<a href='{{URL::to('company')}}' class='btn btn-lg btn-primary' title='Search for specific accounts'>Account Views</a>


				</div>
				<div id="mapbtn" style="text-align:left; padding-left:10%;padding-top:110px">
					<a href='{{route('findme')}}' class='btn btn-lg btn-info' title='Explore map views'>Map Views</a>


				</div>
				@can('view_projects')
					<div id="projectbtn" style="text-align:left; padding-left:70%;padding-top:10px">
	<a href="{{route('projects.index')}}" class="btn btn-lg btn-primary" title='Search for specific construction projects'>Construction Project Views</a>
			</div>
			@endcan
				<div id="branchbtn" style="text-align:left; padding-left:70%;padding-top:50px">
					<a href='{{route('branches.index')}}' class='btn btn-lg btn-warning' title='Explore Branches and their national account locations'>Branch Views</a>


				</div>

				<div id="peoplebtn" style="text-align:left; padding-left:50%;padding-top:100px">
					<a href='{{route('person.index')}}' class='btn btn-lg btn-success' title='Search for people'>People Views</a>


				</div>


		</div>
	</div>
	<?php $newstand = new \App\News;
	$news= $newstand->currentNews();?>
	@if(! $news->isEmpty())
		@include('news.newsmodal')
	@endif

@endif

@include('partials._newsscript')
@endsection
