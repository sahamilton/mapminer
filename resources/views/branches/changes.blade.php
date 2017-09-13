@extends('site.layouts.default')

{{-- Page title --}}
@section('title')
Review Branch Changes
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="container">
	

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#branch"><strong>Branch Additions</strong></a></li>
  <li><a data-toggle="tab" href="#team"><strong>Branch Deletions</strong></a></li>


</ul>

{{dd($data)}}
<form method="post" name="changebranches" action ="{{route('branches.change')}}" >
{{csrf_field()}}
<div class="tab-content">
    <div id="branch" class="tab-pane fade in active">
      @include('branches/partials/_adds')
    </div>
	<div id="team" class="tab-pane fade in">
      @include('branches/partials/_deletes')
    </div>
	
</div>	
<input type="submit" class="btn btn-success" value="Update Branches" />
</form>
</div>
@include('partials/_scripts')
@stop