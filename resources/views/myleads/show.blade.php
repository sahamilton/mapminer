@extends('site.layouts.default')
@section('content')

<h2>{{$mylead->businessname}}</h2>
<p><a href="{{route('myleads.index')}}">Return to all my leads</a></p>
 <button type="button" class="btn btn-info " data-toggle="modal" data-target="#closelead">Close Lead</button>
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
   

  </div>





</div>
@php
$lead = $mylead;
@endphp
@include ('myleads.partials._closeleadform')
@include('myleads.partials.map')
@include('partials._scripts');

@endsection
