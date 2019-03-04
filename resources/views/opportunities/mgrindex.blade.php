@extends('site.layouts.default')
@section('content')

<div class="container">
<h2>My Teams  Dashboard</h2>
@if($data['branches']->count()>1)

<div class="col-sm-4">
<form name="selectbranch" method="post" action="{{route('branches.dashboard')}}" >
@csrf

 <select class="form-control input-sm" id="branchselect" name="branch" onchange="this.form.submit()">
  @foreach ($data['branches'] as $branch)
    <option value="{{$branch->id}}">{{$branch->branchname}}</option>
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
</div>

@include('partials._scripts')
@endsection