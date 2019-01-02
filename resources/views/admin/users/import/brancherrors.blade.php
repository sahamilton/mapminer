@extends('admin.layouts.default')

@section('content')
<div class="container" style="margin-bottom:80px">
	<h4>Import Errors</h4>
	<div class="alert alert-warning">
		<p>Fix these errors and reimport</p>
	</div>

	    	@if(isset($importerrors))
	    	 	@include('admin.users.import.partials._brancherrors')
	    	@endif
	    
</div>

@endsection