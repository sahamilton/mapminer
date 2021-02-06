@extends('admin.layouts.default')
@section('content')

<h2>All Feedback</h2>

<div class="float-right">
<a href="{{{ route('feedback.create') }}}" class="btn btn-small btn-info iframe">

<i class="fas fa-plus-circle " aria-hidden="true"></i>

 Create New Feedback</a>
</div>
<p>
	<a href="{{route('feedback.export')}}">
		<i class="fas fa-file-download"></i> 
		Export Feedback
	</a>
</p>
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
	  <a class="nav-link nav-item active" 
	      id="open-tab" 
	      data-toggle="tab" 
	      href="#open" 
	      role="tab" 
	      aria-controls="open" 
	      aria-selected="true">
	    <strong> Open</strong>
	  </a>
	  
	  <a class="nav-item nav-link" 
	      id="closed-tab" 
	      data-toggle="tab" 
	      href="#closed" 
	      role="tab" 
	      aria-controls="closed" 
	      aria-selected="false">
	    <strong> Closed</strong>
	  </a>

	</div>
</nav>
<div class="tab-content">
    <div id="open" class="tab-pane show active">
		@include('feedback.partials._open')
	</div>


    <div id="closed" class="tab-pane fade">

		@include('feedback.partials._closed')

	</div>
</div>



@include('partials._modal')
@include('partials._scripts')
@endsection
