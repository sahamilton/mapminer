@extends('site.layouts.default')
@section('content')
<div class="container">
<h2>{{$data['branches']->first()->branchname}} Branch Opportunities</h2>

<p><a href="{{route('dashboard.index')}}">Return To Branch Dashboard</a></p>
@php $activityTypes = \App\ActivityType::all(); @endphp
@if(count($myBranches)>1)

<div class="col-sm-4">
<form name="selectbranch" method="post" action="{{route('opportunity.branch')}}" >
@csrf

 <select class="form-control input-sm" id="branchselect" name="branch" onchange="this.form.submit()">
  @foreach ($myBranches as $key=>$branch)
    <option {{$data['branches']->first()->id == $key ? 'selected' : ''}} value="{{$key}}">{{$branch}}</option>
  @endforeach 
</select>

</form>
</div>
@endif
<div class="row">
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
	  <a class="nav-link nav-item active" 
	      id="summary-tab" 
	      data-toggle="tab" 
	      href="#summary" 
	      role="tab" 
	      aria-controls="summary" 
	      aria-selected="true">
	    <strong> Open</strong>
	  </a>
	  
	  <a class="nav-item nav-link" 
	      id="closedwon-tab" 
	      data-toggle="tab" 
	      href="#closedwon" 
	      role="tab" 
	      aria-controls="closedwon" 
	      aria-selected="false">
	    <strong> Closed - Won</strong>
	  </a>

	  <a class="nav-item nav-link" 
	      id="closedlost-tab" 
	      data-toggle="tab" 
	      href="#closedlost" 
	      role="tab" 
	      aria-controls="closedlost" 
	      aria-selected="false">
	    <strong> Closed - Lost</strong>
	  </a>

	</div>
</nav>
 <div class="tab-content" id="nav-tabContent">
    <div id="summary" class="tab-pane show active">
    	
			@include('opportunities.partials._tabopenopportunities')

		
	</div>

    <div id="closedwon" class="tab-pane fade">
		 @include('opportunities.partials._tabclosedwonopportunities')
   </div>
   <div id="closedlost" class="tab-pane fade">
		 @include('opportunities.partials._tabclosedlostopportunities')
   </div>
</div>  
   
  

</div>
@include('partials._modal')
@include('partials._opportunitymodal')
@include('opportunities.partials._closemodal')
@include('opportunities.partials._activitiesmodal')
@include('partials._scripts')
</div>
@endsection