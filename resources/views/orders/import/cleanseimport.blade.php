@extends('site.layouts.default')
@section('content')
<h2>Cleanse Import Entries</h2>
<p class="float-right">
		<a href="{{route('orderimport.flush')}}" class="btn btn-danger" style="padding-right:10px" >Start New Import</a>
		<a href="{{route('orderimport.finalize')}}" class="btn btn-success" style="padding-left:10px">Complete Import</a></p>
	<div class="row"></div>
	<nav>
	  <div class="nav nav-tabs" id="nav-tab" role="tablist">
		  <a class="nav-link nav-item active" 
		      id="create-tab" 
		      data-toggle="tab" 
		      href="#create" 
		      role="tab" 
		      aria-controls="create" 
		      aria-selected="true">
		    <strong>Companies to Create ({{$data['missing'] ? count($data['missing']) : 0}})</strong>
		  </a>
		<a class="nav-link nav-item" 
		      id="matching-tab" 
		      data-toggle="tab" 
		      href="#matching" 
		      role="tab" 
		      aria-controls="matching" 
		      aria-selected="true">
		    <strong>Matching Addresses ({{count($data['matching'])}})</strong>
		  </a>

		  <a class="nav-link nav-item" 
		      id="matchco-tab" 
		      data-toggle="tab" 
		      href="#matchco" 
		      role="tab" 
		      aria-controls="matchco" 
		      aria-selected="true">
		    <strong>Matching Companies ({{count($data['companymatch'])}})</strong>
		  </a>
		  
		  
	    


		</div>
	</nav>

	<div class="tab-content" id="nav-tabContent">
	    <div id="create" class="tab-pane show active">
	    	 @include('orders.import.partials._newcompanies')
	    </div>
	    <div id="matching" class="tab-pane show ">
	     	@include('orders.import.partials._matchingaddresses')
	    </div>
	   <div id="matchco" class="tab-pane show ">
	     	@include('orders.import.partials._matchingcompanies')
	    </div>
	   
	    
	</div>

@include('partials._scripts')
@endsection