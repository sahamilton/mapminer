@extends('site.layouts.default')
@section('content')
<div class="container" style="clear:both">
<h2>{{$data['branches']->first()->branchname}} Dashboard</h2>
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

	<div class="col-sm-3 float-left">
		<div class="card">
			<div class="card-header">
				<h4>Summary</h4>
			</div>
			<div class="card-body">
				<p><strong>Leads:</strong><a href="{{route('lead.branch',$data['branches']->first()->id)}}">{{$data['branches']->first()->leads_count}}</a></p>
				<p><strong>Opportunities:</strong><a href="{{route('opportunities.branch',$data['branches']->first()->id)}}">{{$data['branches']->first()->opportunities_count}}</a></p>
				<p><strong>Won:</strong>{{$data['branches']->first()->won}}</p>
				<p><strong>Lost:</strong>{{$data['branches']->first()->lost}}</p>
				<p><strong>Activities:</strong><a href="{{route('activity.branch',$data['branches']->first()->id)}}">{{$data['branches']->first()->activities_count}}</a></p>
			</div> 
			<div class="card-footer"></div>
			</div>
	</div>
	<div class="float-left">
		<div class="card">
			<div class="card-header">
				<h4>Weekly Activity</h4>
			</div>
			<div class="card-body">
				  <canvas id="ctx" width="300" height="400" ></canvas>
				@include('activities.partials._mchart')
			</div>
		</div>
	</div>
	<div class="float-left">
		<div class="card">
			<div class="card-header">
				<h4>Sales Funnel</h4>
			</div>
			<div class="card-body">
				@include('branches.partials._funnel')
			</div>
		</div>
	</div>
</div>
@include('branches.partials._upcoming')
<div class="container">

</div>
@include('partials._scripts')
@endsection
