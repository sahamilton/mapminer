@extends('site.layouts.default')
@section('content')

<div class="container">
<h2>My Teams  Dashboard</h2>
@if(count($myBranches)>1)

<div class="col-sm-4">
<form name="selectbranch" method="post" action="{{route('branches.dashboard')}}" >
@csrf

 <select class="form-control input-sm" id="branchselect" name="branch" onchange="this.form.submit()">
  @foreach ($myBranches as $key=>$branch)
    <option {{$data['branches']->first()->id == $key ? 'selected' : ''}} value="{{$key}}">{{$branch}}</option>
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
      <div class="container">
        <h4>Wins vs Sales Appts</h4>
          <div id="series_chart_div" 
          style="width: 600px; height: 500px;float:left" 
         > 
            
            @include('opportunities.partials._bubble')
          </div>
      
        <div style="width: 400px; height: 300px;float:right" >
          <h4>Branch Activities</h4>
          <canvas id="ctx" width="400" height="400" ></canvas>
          @include('activities.partials._mchart')
        </div>
      </div>
      <div class="col-sm-5">
        <h4>Branch Pipeline</h4>
        @include('branches.partials._funnel')
      </div>
      <div id="summary" class="tab-pane fade">
        @include('opportunities.partials._summary')
      </div>
    </div>
  </div>
</div>

@include('partials._scripts')
@endsection