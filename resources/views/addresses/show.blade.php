@extends('site.layouts.default')
@section('content')
@php $statuses = [1=>'offered',2=>'owned']; @endphp
@include('companies.partials._searchbar')
<h2>{{$location->businessname}}</h2>
<p>
    @if($location->company)
      <i>A location of <a href="{{ route('company.show', $location->company->id) }}">{{$location->company->companyname}}</a></a></i>
    @endif
</p>

@include('addresses.partials._ranking')

@include('addresses.partials._opportunity')

<p>Location Source: {{$location->leadsource ? $location->leadsource->source : 'unknown'}}
{{$location->createdBy ? "Created by " . $location->createdBy->person->fullname() : ''}}</p>
@if($location->assignedToBranch->count()>0)
<strong>Assigned to:</strong>

  @foreach ($location->assignedToBranch as $branch)

    @if(in_array($branch->id,array_keys($myBranches)))
      @php $owned=true; @endphp
    @endif
  <li><a href="{{route('branches.show',$branch->id)}}">{{$branch->branchname}}</a> - @if(isset($statuses[$branch->pivot->status_id])) 
    {{$statuses[$branch->pivot->status_id]}}
  @endif
  
</li>

  @endforeach
@if(isset($owned))
 <!--need to check if the address is in my teams leads or sales ops -->

  <div class="row">
    <div class="col-2-md">
      <a class="btn btn-warning"
           data-toggle="modal" 
           data-target="#reassign" 
           
           href="#">Reassign</a>
</div>
</div>
@endif
@endif
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
@if($location->opportunities->count()>0)
<a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#opportunities"
        id="opportunity-tab"
        role="tab"
        aria-controls="opportunities"
        aria-selected="false">

    <strong>Opportunities ({{$location->opportunities->count()}})</strong>



@endif





  @if($location->addressable_type == 'project')
 <a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#projectdetails"
        id="project-tab"
        role="tab"
        aria-controls="projectdetails"
        aria-selected="false">

    <strong>Project Details</strong>

  @endif
  @if($location->addressable_type == 'weblead')
    <a class="nav-item nav-link"  
          data-toggle="tab" 
          href="#weblead"
          id="weblead-tab"
          role="tab"
          aria-controls="weblead"
          aria-selected="false">

      <strong>Lead Details</strong>
    </a>
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
  <a class="nav-item nav-link" 
      data-toggle="tab" 
      href="#note"
      id="note-tab"
      role="tab"
      aria-controls="note"
      aria-selected="false">
        <strong>Notes</strong>
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
  @if($location->currentRating())
<a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#rating"
        id="rating-tab"
        role="tab"
        aria-controls="rating"
        aria-selected="false">

    <strong> Ratings ({{number_format($location->currentRating(),1)}})</strong>
  </a>
  @endif


</div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div id="details" class="tab-pane show active">
     @include('addresses.partials._tabdetails')
    </div>
    @if($location->opportunities->count() > 0)
    <div id="opportunities" class="tab-pane fade">

        @php $data['opportunities'] = $location->opportunities; 

        $activityTypes = \App\ActivityType::all();
        @endphp
        @include('addresses.partials._tabopportunities2')

    </div>
    @endif
        @if($location->addressable_type == 'weblead')
    <div id="weblead" class="tab-pane fade">
      
       @include('addresses.partials._tabwebleads') 

    </div>
    @endif
    @if($location->addressable_type == 'project')
    <div id="projectdetails" class="tab-pane fade">
      @php $project = $location->project @endphp
       @include('projects.partials._projectdetails') 

    </div>

    <div id="contacts" class="tab-pane fade">
      @include('projects.partials._companylist')
      
      
      

    </div>
    @else
    <div id="contacts" class="tab-pane fade">
      @include('addresses.partials._tabcontacts')

    </div>

    @endif    

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
    <div id="note" class="tab-pane fade">
     @include('notes.partials._table')
    </div>

  </div>


@include('partials._modal')
@include('opportunities.partials._closemodal')
@include('addresses.partials._reassignlead')
@include('addresses.partials._rateaddressform')
@include('partials._scripts')
@include('addresses.partials.map')


@endsection
