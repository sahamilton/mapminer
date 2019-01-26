@extends ('admin.layouts.default')
@section('content')
<h2>Prospect Source - {{$leadsource->source}}</h2>

<p><a href="{{route('leadsource.export',$leadsource->id)}}"><i class="fas fa-cloud-download-alt" aria-hidden="true"></i></i>  Export owned and closed prospects</a></p>
<p><a href="{{route('leadsource.index')}}">Return to all Prospect sources</a></p>
<div class="nav nav-tabs" id="nav-tab" role="tablist">
  <a class="nav-link nav-item active" 
		data-toggle="tab" href="#details">
			<strong>Details</strong>
		</a>

	<a class="nav-item nav-link"  data-toggle="tab" href="#team">
			<strong>Team</strong>
		</a>
	
</div>

<div class="tab-content">
	
	 <div id="details" class="tab-pane show active">
	@include('leadsource.partials._tabdetails')
	</div>
	
	<div id="team" class="tab-pane fade ">
	@include('leadsource.partials._tabteam')
	</div>


	
</div>
@include('partials._scripts')
@endsection
