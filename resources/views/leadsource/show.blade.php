@extends ('admin.layouts.default')
@section('content')
<h2>Lead Source - {{$leadsource->source}}</h2>
<p><a href="{{route('leadsource.index')}}">Return to all Leads sources</a></p>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#map"><strong>Map View</strong></a></li>
  <li><a data-toggle="tab" href="#details"><strong>Details</strong></a></li>
  <li><a data-toggle="tab" href="#leads"><strong>Leads</strong></a></li>
  <li><a data-toggle="tab" href="#team"><strong>Team</strong></a></li>
    <li><a data-toggle="tab" href="#stats"><strong>Stats</strong></a></li>

  

</ul>

<div class="tab-content">
<div id="map" class="tab-pane fade in active">
@include('leadsource.partials._tabmap')
</div>
<div id="details" class="tab-pane fade in ">
@include('leadsource.partials._tabdetails')
</div>
<div id="leads" class="tab-pane fade in ">
@include('leadsource.partials._tableads')
</div>
<div id="team" class="tab-pane fade in ">
@include('leadsource.partials._tabteam')
</div>
<div id="stats" class="tab-pane fade in ">
@include('leadsource.partials._tabstats')
</div>
</div>
@include('partials._scripts')
@endsection