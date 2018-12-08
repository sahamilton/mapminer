@extends('site/layouts/default')

{{-- Page title --}}
@section('title')
Edit Branch ::
@parent
@endsection

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>Edit Branch</h3>
</div>


<ul class="nav nav-tabs">

  <li class="nav-item active">
    <a class="nav-link" data-toggle="tab" href="#branch">
      <strong>Branch Location</strong>
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link"  
    data-toggle="tab" 
    href="#team">
      <strong>Branch Team</strong>
    </a>
  </li>



</ul>
<?php 
	
    foreach ($branch->servicelines as $serving){
		$served[] = $serving->id;
	}
$buttonLabel = 'Edit Branch';?>
<form method="post" action ="{{route('branches.update', $branch->id)}}" >
<input type="hidden" name="_method" value = 'patch' />
	@csrf
<div class="tab-content" id="myTabContent">
  <div 
    class="tab-pane fade show active" 
    id="branch" 
    role="tabpanel" 
    aria-labelledby="home-tab">
      @include('branches/partials/_form')
  </div>
	<div 
    class="tab-pane fade" 
    id="team" 
    role="tabpanel" 
    aria-labelledby="contact-tab">
      @include('branches/partials/_team')
  </div>
	
</div>
<input type="submit" class="btn btn-success" value="Edit Branch" />
</form>

@endsection
