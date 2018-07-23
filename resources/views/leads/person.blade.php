@extends ('admin.layouts.default')
@section('content')



<h3>Prospects assigned to {{$leads->fullName()}}</h3>

@if(! isset($source))
<p><a href="{{route('leadsource.index')}}">From All Sources</a></p>

@else

<p>From <a href="{{route('leadsource.show',$source->id)}}">{{$source->source}}</a> source</p>
<p><a href="{{route('leads.person',$leads->id)}}">See all {{$leads->firstname}}'s prospects</a></p>
@endif

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#map"><strong>Map View</strong></a></li>
 
  <li><a data-toggle="tab" href="#leads"><strong>Prospects</strong></a></li>


  

</ul>

<div class="tab-content">
<div id="map" class="tab-pane fade in active">
@include('leads.partials._tabpersonsmap')
</div>
<div id="leads" class="tab-pane fade in ">
@include('leads.partials._tabpersonsleads')
</div>

</div>
@include('partials._scripts')
@endsection