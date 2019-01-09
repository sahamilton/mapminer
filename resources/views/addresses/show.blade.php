@extends('site.layouts.default')
@section('content')
@include('companies.partials._searchbar')
<h2>{{$location->businessname}}</h2>
<p>
    @if($location->company)
      <i>A location of <a href="{{ route('company.show', $location->company->id) }}">{{$location->company->companyname}}</a></a></i>
    @endif
</p>


  @include('addresses.partials._addressaction')

@if($location->opportunities)
@include('addresses.partials._opportunity')
@endif
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
    <strong> Details</strong>
  </a>
    <a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#contacts"
        id="contact-tab"
        role="tab"
        aria-controls="contacts"
        aria-selected="false">

    <strong>Contacts</strong>
  </a>
  <a class="nav-item nav-link" 
      data-toggle="tab" 
      href="#activities"
      id="activities-tab"
      role="tab"
      aria-controls="activities"
      aria-selected="false">
        <strong>Activities</strong>
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
  <a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#watchers"
        id="watcher-tab"
        role="tab"
        aria-controls="watchers"
        aria-selected="false">

    <strong> Watchers ({{$location->watchedBy->count()}})</strong>
  </a>
    @if($location->addressable_type == 'customer')
  <a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#business"
        id="business-tab"
        role="tab"
        aria-controls="business"
        aria-selected="false">

    <strong>Recent Business</strong>
  </a>
  @endif
  @if($location->has('ranking'))
<a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#rating"
        id="rating-tab"
        role="tab"
        aria-controls="rating"
        aria-selected="false">

    <strong> Ratings</strong>
  </a>
  @endif


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
    <div id="watchers" class="tab-pane fade">
     @include('addresses.partials._tabwatcher')
    </div>
   <div id="business" class="tab-pane fade">
     @include('addresses.partials._taborders')
    </div>
    <div id="rating" class="tab-pane fade">
     @include('addresses.partials._tabratings')
    </div>

  </div>





</div>

@include('partials._modal');
@include('addresses.partials._rateaddressform')
@include('addresses.partials.map')
@include('partials._scripts');


@endsection
