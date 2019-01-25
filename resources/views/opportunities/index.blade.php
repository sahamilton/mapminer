@extends('site.layouts.default')
@section('content')
@include('companies.partials._searchbar')

@include('maps.partials._form')

<h2>{{$data['branches']->first()->branchname}} Branch Dashboard</h2>

 <nav>

  <div class="nav  nav-tabs"  id="nav-tab"  role="tablist">
    <a class="nav-item nav-link active"
    id="nav-leads-tab"
    data-toggle="tab"
    href="#nav-leads"
    role="tab"
    aria-controls="nav-leads"
    aria-selected="true">
<strong>Leads</strong>

  <a class="nav-item nav-link "
    id="nav-opportunities-tab"
    data-toggle="tab"
    href="#nav-opportunities"
    role="tab"
    aria-controls="nav-opportunities"
    aria-selected="true">
<strong>Opportunities</strong>
</a>
  
</a>
  <a class="nav-item nav-link"
    id="nav-customers-tab"
    data-toggle="tab"
    href="#nav-customers"
    role="tab"
    aria-controls="nav-customers"
    aria-selected="false">
    <strong>Accounts</strong>
</a> 
<a class="nav-item nav-link"
    id="nav-customers-tab"
    data-toggle="tab"
    href="#nav-contacts"
    role="tab"
    aria-controls="nav-contacts"
    aria-selected="false">
    <strong>Contacts</strong>
</a>   
<a class="nav-item nav-link"
    id="nav-activities-tab"
    data-toggle="tab"
    href="#nav-activities"
    role="tab"
    aria-controls="nav-activities"
    aria-selected="false">
    <strong>Activities</strong>
</a>
 
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show "
    id="nav-opportunities"
    role="tabpanel"
    aria-labelledby="nav-home-tab">
   
   @include('opportunities.partials._tabopportunities')
  </div>
<div class="tab-pane fade show active"
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
     @include('opportunities.partials._tabactivities')
  </div>

  <div class="tab-pane fade"
    id="nav-contacts"
    role="tabpanel"
    aria-labelledby="nav-activities-tab">
   @php $contacts = $data['contacts'];@endphp
    @include('contacts.partials._table')
  </div>

 
</div>

@include('partials._scripts')
@endsection