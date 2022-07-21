@extends('site.layouts.default')
@section('content')

@php $statuses = [1=>'Offered to',2=>'Owned by','4'=>'Owned by*']; @endphp

<h2>{{$location->businessname}}</h2>

<p>
    @if($location->company)
      <i>A location of <a href="{{ route('company.show', $location->company->id) }}">{{$location->company->companyname}}</a></a></i>
     @if($location->company->salesnotes->count() >0)
        <p><i>See how to sell to <a href="{{route('salesnotes.show', $location->company->id)}}">{{$location->company->companyname}}</a></p>
     @endif
    @endif
</p>
@if($owned && $location->user_id === auth()->user()->id)
<!--- insert livewire addressbranch here ---->

  <livewire:address-branch :address="$location->id" />

  
@endif

@if(auth()->user()->hasRole(['branch_manager', 'staffing_specialist', 'market_manager']))
      
  <p><a href="{{route('branch.leads')}}" >Return to branch leads</a></p>
  
  <a href="https://www.google.com/search?q={{$location->businessname. " " .$location->fullAddress()}} news" 
    title="Google search for {{$location->businessname}}" 
    target="_blank">
    <img src="{{asset('/assets/icons/google.png')}}" width="25" height="25" 
    title="Google search for {{$location->businessname}}" 

  />Search for {{$location->businessname}}</a>
@endif

@if($location->assignedToBranch)
@php $branch = $location->assignedToBranch->first() @endphp

@endif
@include('addresses.partials._leadstatus')



<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
  <a class="nav-link nav-item active" 
      id="details-tab" 
      data-toggle="tab" 
      href="#details" 
      role="tab" 
      aria-controls="details" 
      aria-selected="true">
    <strong>  Details</strong>
  </a>
  <a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#contacts"
        id="contact-tab"
        role="tab"
        aria-controls="contacts"
        aria-selected="false">

    <strong>Contacts ({{$location->contacts->count()}})</strong>
  </a>
  <a class="nav-item nav-link" 
        data-toggle="tab" 
        href="#activities"
        id="activities-tab"
        role="tab"
        aria-controls="activities"
        aria-selected="false">
          <strong>Activities ({{$location->activities->count()}})</strong>
    </a>
@if(isset($owned))
<a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#opportunities"
        id="opportunity-tab"
        role="tab"
        aria-controls="opportunities"
        aria-selected="false">

    <strong>Opportunities ({{$location->opportunities->count()}})</strong>



@endif



    
 
    
  
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
 
  @if($location->company && $location->company->salesnotes)
        <a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#salesnotes"
        id="salesnotes-tab"
        role="tab"
        aria-controls="salesnotes"
        aria-selected="false">

    <strong> Sales Notes</strong>
  </a>
  @endif


</div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div id="details" class="tab-pane show active">
     @include('addresses.partials._tabdetails')
    </div>
    @if(isset($owned))
      <div id="opportunities" class="tab-pane fade">

          @php $data['opportunities'] = $location->opportunities; 

          
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

    <div id="company" class="tab-pane fade">
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
    <div id="business" class="tab-pane fade">
     @include('addresses.partials._taborders')
    </div>
    <div id="rating" class="tab-pane fade">
     @include('addresses.partials._tabratings')
    </div>
    <div id="note" class="tab-pane fade">
     @include('notes.partials._table')
    </div>
    <div id="salesnotes" class="tab-pane fade">
      @if ($location->company && $location->company->salesnotes)
      @php $data = $location->company->salesnotes; @endphp
     @include('addresses.partials._shownote')
     @endif
    </div>

  </div>
@include('partials._modal')
@if($owned)
  @include('addresses.partials._deleteleadmodal')
  @include('addresses.partials._removecampaignmodal')
@endif
@include('addresses.partials._addresscampaignmodal')
@if($campaigns->count())
  @include('addresses.partials._removecampaignmodal')
@endif
@include('opportunities.partials._closemodal')
@include('addresses.partials._reassignlead')
@include('addresses.partials._rateaddressform')
@include('partials._scripts')
@include('addresses.partials.map')



@endsection
