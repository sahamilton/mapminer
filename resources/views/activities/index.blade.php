@extends('site.layouts.default')
@section('content')

<h1>{{$title}}</h1> 
<p><a href="{{route('dashboard.index')}}">Return To Branch Dashboard</a></p>

@if(count($myBranches)>1)

<div class="col-sm-4">
<form name="selectbranch" method="post" action="{{route('activities.branch')}}" >
@csrf

 <select class="form-control input-sm" id="branchselect" name="branch" onchange="this.form.submit()">
  @foreach ($myBranches as $key=>$branch)
    <option {{$data['branches']->first()->id == $key ? 'selected' : ''}} value="{{$key}}">{{$branch}}</option>
  @endforeach 
</select>

</form>
</div>
@endif
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
	  <a class="nav-link nav-item active" 
	      id="summary-tab" 
	      data-toggle="tab" 
	      href="#summary" 
	      role="tab" 
	      aria-controls="summary" 
	      aria-selected="true">
	    <strong> Summary</strong>
	  </a>
	  <a class="nav-link nav-item" 
	      id="upcoming-tab" 
	      data-toggle="tab" 
	      href="#upcoming" 
	      role="tab" 
	      aria-controls="upcoming" 
	      aria-selected="true">
	    <strong> Upcoming</strong>
	  </a>
	  <a class="nav-item nav-link" 
	      id="details-tab" 
	      data-toggle="tab" 
	      href="#details" 
	      role="tab" 
	      aria-controls="details" 
	      aria-selected="false">
	    <strong> Completed</strong>
	  </a>

	</div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div id="summary" class="tab-pane show active">
    	<canvas id="ctb" width="500" height="300" ></canvas>
			@include('charts._branchactivitiestype')
		
	</div>

	<div id="summary" class="tab-pane fade">
    	
			@include('activities.partials._upcoming')
		
	</div>

    <div id="details" class="tab-pane fade">
		@include('activities.partials._completed')
   </div>
</div>
@include('partials._scripts')

@endsection