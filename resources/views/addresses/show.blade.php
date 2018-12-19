@extends('site.layouts.default')
@section('content')

<h2>{{$location->businessname}}</h2>
<p>Location Source: {{$location->addressType[$location->addressable_type]}}</p>
@include('maps.partials._form')
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
  <a class="nav-link nav-item active" 
      id="details-tab" 
      data-toggle="tab" 
      href="#details" 
      role="tab" 
      aria-controls="details" 
      aria-selected="true">
    <strong>Location Details</strong>
  </a>
    <a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#contacts"
        id="contact-tab"
        role="tab"
        aria-controls="contacts"
        aria-selected="false">

    <strong>Location  Contacts</strong>
  </a>
  <a class="nav-item nav-link" 
      data-toggle="tab" 
      href="#activities"
      id="activities-tab"
      role="tab"
      aria-controls="activities"
      aria-selected="false">
        <strong>Location Activities</strong>
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
     @include('addresses.partials._tabdetails')
    </div>
    <div id="contacts" class="tab-pane fade">

      @include('addresses.partials._tabcontacts')

    </div>
    <div id="activities" class="tab-pane fade">
     @include('addresses.partials._tabactivities')
    </div>
    <div id="team" class="tab-pane fade">
     @include('addresses.partials._tabsalesteam')
    </div>
    <div id="branch" class="tab-pane fade">
     @include('addresses.partials._tabbranches')
    </div>
   

  </div>





</div>
@php
$lead = $location;
@endphp
@include('partials/_modal')



@include('addresses.partials.map')
@include('partials._scripts');

@endsection
