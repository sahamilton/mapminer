@extends('site.layouts.default')
@section('content')

<h2>{{$address->businessname}}</h2>
<p><a href="{{route('mobile.index')}}">Return to Mobile View</a></p>
<p>
    @if($address->company)
      <i>A location of <a href="{{ route('company.show', $address->company->id) }}">{{$address->company->companyname}}</a></a></i>
    @endif
</p>




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
@if($address->openOpportunities->count()>0)
<a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#opportunities"
        id="opportunity-tab"
        role="tab"
        aria-controls="opportunities"
        aria-selected="false">

    <strong>Open Opportunities ({{$address->openOpportunities->count()}})</strong>

@endif


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



</div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div id="details" class="tab-pane show active">
     @include('mobile.partials._tabdetails')
    </div>
    @if($address->opportunities->count() > 0)
    <div id="opportunities" class="tab-pane fade">

       

    </div>
    @endif


    <div id="contacts" class="tab-pane fade">
      @include('mobile.partials._tabcontacts')

    </div>
  

    <div id="activities" class="tab-pane fade">
     
    </div>
    

  </div>


@include('partials._modal')
@include('opportunities.partials._closemodal')
@include('partials._scripts')
@include('mobile.partials.map')


@endsection
