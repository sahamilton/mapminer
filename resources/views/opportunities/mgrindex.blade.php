@extends('site.layouts.default')
@section('content')

<div class="container">
  <h2>{{$data['team']['me']->fullName}}'s  Dashboard</h2>

  @if($data['team']['team']->count()>1)

    @include('branches.partials._branchselector')
  @endif
  @include('branches.partials._periodselector')
  @include('opportunities.partials._dashboardselect')
  <nav>
    <div class="nav  nav-tabs"  id="nav-tab"  role="tablist">
      <a class="nav-item nav-link active"
      id="nav-dashboard-tab"
      data-toggle="tab"
      href="#dashboard"
      role="tab"
      aria-controls="nav-dashboard"
      aria-selected="true">
      <strong>Dashboard</strong></a>
     
      <a class="nav-item nav-link "
        id="nav-summary-tab"
        data-toggle="tab"
        href="#summary"
        role="tab"
        aria-controls="nav-summary"
        aria-selected="true">
      <strong>Summary</strong>
      </a>

    </div>
  </nav>

  <div class="tab-content" id="nav-tabContent">
    <div id="dashboard" class="tab-pane show active">
      @include('branches.partials._dashboard')
    </div>
    <div id="summary" class="tab-pane fade">
      @include('opportunities.partials._summary')
    </div>
  </div>
</div>

@include('partials._scripts')
@endsection