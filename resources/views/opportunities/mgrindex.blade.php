@extends('site.layouts.default')
@section('content')

<div class="container">
<h2>My Teams  Dashboard</h2>
@if($data['team']['team']->count()>1)

<div class="col-sm-4">
<form name="selectmanager" method="post" action="{{route('managers.dashboard')}}" >
@csrf

 <select class="form-control input-sm" id="managerselect" name="manager" onchange="this.form.submit()">
  @foreach ($data['team']['team'] as $mgr)
    <option value="{{$mgr->id}}">{{$mgr->fullName()}}</option>
  @endforeach 
</select>

</form>
</div>
@endif
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
    @if($data['team']['team']->count()>0)

      <a class="nav-item nav-link "
          id="nav-team-tab"
          data-toggle="tab"
          href="#team"
          role="tab"
          aria-controls="nav-team"
          aria-selected="true">
        <strong>Team</strong>
        </a>


    @endif
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
     <div id="team" class="tab-pane fade">
        @include('opportunities.partials._team')
      </div>
      <div id="summary" class="tab-pane fade">
        @include('opportunities.partials._summary')
      </div>
    </div>
  </div>
</div>

@include('partials._scripts')
@endsection