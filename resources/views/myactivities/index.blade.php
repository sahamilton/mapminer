@extends('site.layouts.default')
@section('content')
@include('companies.partials._searchbar')
@include('maps.partials._form')
<h2>My Dashboard</h2>
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
  <a class="nav-link nav-item active" 
        data-toggle="tab" 
        href="#contacts"
        id="contact-tab"
        role="tab"
        aria-controls="contacts"
        aria-selected="false">

    <strong>My Contacts</strong>
  <a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#leads"
        id="leads-tab"
        role="tab"
        aria-controls="leads"
        aria-selected="false">

    <strong>My Leads</strong>
  </a>
  <a class="nav-item nav-link" 
      data-toggle="tab" 
      href="#activities"
      id="activities-tab"
      role="tab"
      aria-controls="activities"
      aria-selected="false">
        <strong>My Activities</strong>
  </a>

  <a class="nav-item nav-link" 
      data-toggle="tab" 
      href="#team"
      id="team-tab"
      role="tab"
      aria-controls="team"
      aria-selected="false">
        <strong>My Ratings</strong>
  </a>

  <a class="nav-item nav-link" 
      data-toggle="tab" 
      href="#branch"
      id="branch-tab"
      role="tab"
      aria-controls="branch"
      aria-selected="false">
        <strong>My Notes</strong>
  </a>



</div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div id="leads" class="tab-pane show active">
      
      @include('leads.partials._tablist')
    </div>

    <div id="contacts" class="tab-pane fade">

      @include('contacts.partials._table')

    </div>
    <div id="activities" class="tab-pane fade">
      @include('activities.partials._table')
    </div>
    <div id="team" class="tab-pane fade">
      @include('ratings.partials._table')
    </div>
    <div id="branch" class="tab-pane fade">
      @include('notes.partials._table')
    </div>


  </div>


@include('partials._scripts');


@endsection
