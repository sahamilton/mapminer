@extends('admin.layouts.default')
@section('content')
<div class="container">
<h4>User Import</h4>

<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
	  <a class="nav-link nav-item active" 
	      id="delete-tab" 
	      data-toggle="tab" 
	      href="#delete" 
	      role="tab" 
	      aria-controls="delete" 
	      aria-selected="true">
	    <strong>Users to Delete ({{count($data['deleteUsers'])}})</strong>
	  </a>

	  <a class="nav-link nav-item" 
	      id="add-tab" 
	      data-toggle="tab" 
	      href="#add" 
	      role="tab" 
	      aria-controls="add" 
	      aria-selected="true">
	    <strong>Users to Create ({{count($data['newUsers'])}})</strong>
	  </a>

	  <a class="nav-link nav-item" 
	      id="missing-tab" 
	      data-toggle="tab" 
	      href="#missing" 
	      role="tab" 
	      aria-controls="missing" 
	      aria-selected="true">
	    <strong>Missing Managers ({{count($data['noManagers'])}})</strong>
	  </a>
    


	</div>
</nav>

<div class="tab-content" id="nav-tabContent">
    <div id="delete" class="tab-pane show active">
    	 @include('admin.users.import.partials._missingusers')
    </div>
    <div id="add" class="tab-pane show ">
     	@include('admin.users.import.partials._newusers')
    </div>
    <div id="missing" class="tab-pane show ">
     	@include('admin.users.import.partials._missingmgrs')
    </div>
</div>

</div>
@include('partials._scripts')
@endsection