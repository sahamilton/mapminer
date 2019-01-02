@extends('admin.layouts.default')

@section('content')
<div class="container" style="margin-bottom:80px">
	<h4>Import Errors</h4>

	<div class="alert alert-warning">
		<p>Fix these errors and reimport</p>
	</div>
	<nav>
	  <div class="nav nav-tabs" id="nav-tab" role="tablist">
		  <a class="nav-link nav-item active" 
		      id="delete-tab" 
		      data-toggle="tab" 
		      href="#email" 
		      role="tab" 
		      aria-controls="delete" 
		      aria-selected="true">
		    <strong>Email Errors ({{isset($data['errors']['emails']) ? count($data['errors']['emails']) : 0 }})</strong>
		  </a>
		<a class="nav-link nav-item" 
		      id="missing-tab" 
		      data-toggle="tab" 
		      href="#branch" 
		      role="tab" 
		      aria-controls="missing" 
		      aria-selected="true">
		    <strong>Branch Errors ({{isset($data['errors']['branch']) ? $data['errors']['branch']->count() : 0 }})</strong>
		  </a>
		</div>
	</nav>
	<div class="tab-content" id="nav-tabContent">
	    <div id="email" class="tab-pane show active">
	    	@if(isset($data['errors']['emails']))
	    	 	@include('admin.users.import.partials._emailerrors')
	    	@endif
	    </div>
	    <div id="missing" class="tab-pane show ">
	     	
	    </div>
	    <div id="branch" class="tab-pane show ">
	     	@if(isset($data['errors']['branch']))}}
	     		@include('admin.users.import.partials._brancherrors')
	     	@endif
	    </div>
	    
	</div>
</div>

@endsection