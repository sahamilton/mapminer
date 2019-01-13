@extends('site.layouts.default')
@section('content')
@include('maps.partials._form')
<h2>{{$location->businessname}}</h2>

@include('lead.partials._leadaction')
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
     @include('lead.partials._tabdetails')
    </div>
    <div id="contacts" class="tab-pane fade">

      @include('lead.partials._tabcontacts')

    </div>
    <div id="activities" class="tab-pane fade">
     @include('lead.partials._tabactivities')
    </div>
    <div id="team" class="tab-pane fade">
     @include('lead.partials._tabsalesteam')
    </div>
    <div id="branch" class="tab-pane fade">
     @include('lead.partials._tabbranches')
    </div>
   

  </div>





</div>
@php
$lead = $location;
@endphp
@include('partials/_modal')

@include ('lead.partials._claimleadform')

@include('lead.partials.map')
@include('partials._scripts');

@endsection
