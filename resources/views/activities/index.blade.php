@extends('site.layouts.default')
@section('content')
@include('companies.partials._searchbar')
@include('maps.partials._form')
<h1>Activities</h1> 

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
	  
	  <a class="nav-item nav-link" 
	      id="details-tab" 
	      data-toggle="tab" 
	      href="#details" 
	      role="tab" 
	      aria-controls="details" 
	      aria-selected="false">
	    <strong> Details</strong>
	  </a>

	</div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div id="summary" class="tab-pane show active">
    	@if(isset($data['show']))
		@include('activities.partials._summary')
		@endif
	</div>


    <div id="details" class="tab-pane fade">
		@include('activities.partials._table')
   </div>
</div>
@include('partials._scripts')

@endsection