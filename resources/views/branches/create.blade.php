@extends('site.layouts.default')

{{-- Page title --}}
@section('title')
Create a New Branch
@parent
@endsection

{{-- Page content --}}
@section('content')
<div class="container">
	<div class="page-header">
		<h3>Create a New Branch</h3>
	</div>

<ul class="nav nav-tabs">

  <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#branch"><strong>Branch Location</strong></a></li>
  <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#team"><strong>Branch Team</strong></a></li>



</ul>

<?php $buttonLabel = 'Create Branch';?>
<form method="post" name="createbranch" action ="{{route('branches.store')}}" >
@csrf
<div class="tab-content">
    <div id="branch" class="tab-pane fade in active">
      @include('branches/partials/_form')
    </div>
	<div id="team" class="tab-pane fade in">
      @include('branches/partials/_team')
    </div>
	
</div>	
<input type="submit" class="btn btn-success" value="Add Branch" />
</form>
</div>
@include('partials/_scripts')
@endsection
