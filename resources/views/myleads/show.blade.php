@extends('site.layouts.default')
@section('content')

<h2>{{$mylead->businessname}}</h2>
<p><a href="{{route('myleads.index')}}">Return to all my leads</a></p>
@if($mylead->salesteam->first()->pivot->status_id == 1)
  <button type="button" class="btn btn-info " data-toggle="modal" data-target="#closelead">Claim Lead</button>
@elseif($mylead->salesteam->first()->pivot->status_id == 2)
 <button type="button" class="btn btn-info " data-toggle="modal" data-target="#closelead">Close Lead</button>
@else
<p><strong>Lead Closed: Rated {{$mylead->salesteam->first()->pivot->rating}}</strong></p>
<p><a href="{{route('myclosedleads')}}">See all closed leads</a></p>

@endif
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
  <a class="nav-link nav-item active" 
      id="details-tab" 
      data-toggle="tab" 
      href="#details" 
      role="tab" 
      aria-controls="details" 
      aria-selected="true">
    <strong>Lead Details</strong>
  </a>
    <a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#contacts"
        id="contact-tab"
        role="tab"
        aria-controls="contacts"
        aria-selected="false">

    <strong>Lead  Contacts</strong>
  </a>
  <a class="nav-item nav-link" 
      data-toggle="tab" 
      href="#activities"
      id="activities-tab"
      role="tab"
      aria-controls="activities"
      aria-selected="false">
        <strong>Lead Activities</strong>
  </a>

  <a class="nav-item nav-link" 
      data-toggle="tab" 
      href="#team"
      id="team-tab"
      role="tab"
      aria-controls="team"
      aria-selected="false">
        <strong>Sales Team</strong>
  </a>

  <a class="nav-item nav-link" 
      data-toggle="tab" 
      href="#branch"
      id="branch-tab"
      role="tab"
      aria-controls="branch"
      aria-selected="false">
        <strong>Branches</strong>
  </a>
    


</div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div id="details" class="tab-pane show active">
     @include('myleads.partials._tabdetails')
    </div>
    <div id="contacts" class="tab-pane fade">

      @include('myleads.partials._tabcontacts')

    </div>
    <div id="activities" class="tab-pane fade">
     @include('myleads.partials._tabactivities')
    </div>
    <div id="team" class="tab-pane fade">
     @include('myleads.partials._tabsalesteam')
    </div>
    <div id="branch" class="tab-pane fade">
     @include('myleads.partials._tabbranches')
    </div>
   

  </div>





</div>
@php
$lead = $mylead;
@endphp
@include('partials/_modal')
@include ('myleads.partials._closeleadform')
@include('myleads.partials.map')
@include('partials._scripts');

@endsection
