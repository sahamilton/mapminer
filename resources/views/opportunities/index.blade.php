@extends('site.layouts.default')
@section('content')
<div class="container">
@include('companies.partials._searchbar')

@include('maps.partials._form')
<h2>{{$data['branches']->first()->branchname}} Branch Opportunities</h2>
<p><a href="{{route('dashboard.index')}}">Return To Branch Dashboard</a></p>

@if(count($myBranches)>1)

<div class="col-sm-4">
<form name="selectbranch" method="post" action="{{route('opportunity.branch')}}" >
@csrf

 <select class="form-control input-sm" id="branchselect" name="branch" onchange="this.form.submit()">
  @foreach ($myBranches as $key=>$branch)
    <option {{$data['branches']->first()->id == $key ? 'selected' : ''}} value="{{$key}}">{{$branch}}</option>
  @endforeach 
</select>

</form>
</div>
@endif
<div class="row">
 

   
   @include('opportunities.partials._tabopportunities')



</div>
@include('partials._modal')
@include('partials._opportunitymodal')
@include('opportunities.partials._closemodal')
@include('opportunities.partials._activitiesmodal')
@include('partials._scripts')
</div>
@endsection