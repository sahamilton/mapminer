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
  <li class="active"><a data-toggle="tab" href="#branch"><strong>Branch Location</strong></a></li>
  <li><a data-toggle="tab" href="#team"><strong>Branch Team</strong></a></li>


</ul>
<?php 
	
    foreach ($branch->servicelines as $serving){
		$served[] = $serving->id;
	}
$buttonLabel = 'Edit Branch';?>
<form method="post" action ="{{route('branches.update', $branch->id)}}" >
<input type="hidden" name="_method" value = 'patch' />
	{{csrf_field()}}
<div class="tab-content">
    <div id="branch" class="tab-pane fade in active">
      @include('branches/partials/_form')
    </div>
	<div id="team" class="tab-pane fade in">
      @include('branches/partials/_team')
    </div>
	
</div>
<input type="submit" class="btn btn-success" value="Edit Branch" />
</form>

@endsection