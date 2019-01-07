@extends('site.layouts.default')
@section('content')
@include('companies.partials._searchbar')

@include('maps.partials._form')
<h2>{{$opportunities->first()->branch->branchname}} Branch Dashboard</h2>
 <nav>

  <div class="nav  nav-tabs"
  id="nav-tab"
  role="tablist">
  <a class="nav-item nav-link active"
    id="nav-opportunities-tab"
    data-toggle="tab"
    href="#nav-opportunities"
    role="tab"
    aria-controls="nav-opportunities"
    aria-selected="true">
   Opportunities
  </a>

  <a class="nav-item nav-link"
    id="nav-leads-tab"
    data-toggle="tab"
    href="#nav-leads"
    role="tab"
    aria-controls="nav-leads"
    aria-selected="true">
   Leads
  </a>

  <a class="nav-item nav-link"
    id="nav-customers-tab"
    data-toggle="tab"
    href="#nav-customers"
    role="tab"
    aria-controls="nav-customers"
    aria-selected="false">
    Customers
  </a>
  <a class="nav-item nav-link"
    id="nav-activities-tab"
    data-toggle="tab"
    href="#nav-activities"
    role="tab"
    aria-controls="nav-activities"
    aria-selected="false">
    Activities
  </a>

 
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active"
    id="nav-opportunities"
    role="tabpanel"
    aria-labelledby="nav-home-tab">
   
   @include('opportunities.partials._tabopportunities')
  </div>
<div class="tab-pane fade"
    id="nav-leads"
    role="tabpanel"
    aria-labelledby="nav-leads-tab-tab">
   @include('opportunities.partials._tableads')
  </div>


  <div class="tab-pane fade"
    id="nav-customers"
    role="tabpanel"
    aria-labelledby="nav-customers-tab">
    @include('opportunities.partials._taborders')
  </div>


  <div class="tab-pane fade"
    id="nav-activities"
    role="tabpanel"
    aria-labelledby="nav-activities-tab">
    Activities
  </div>

 
</div>
@include('opportunities.partials._activitiesmodal')
@include('partials._scripts')
@endsection