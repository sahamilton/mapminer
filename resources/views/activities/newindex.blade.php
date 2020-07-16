@extends('site.layouts.default')
@section('content')

<h2>{{$branch->branchname}}</h2>
<h4>Activities</h4>

<p><a href="{{route('branchdashboard.show', $branch->id)}}">
<i class="fas fa-tachometer-alt"></i>
 Return To Branch Dashboard</a></p>
@if(count($myBranches) > 1)

<div class="col-sm-4">
<form name="selectbranch" method="post" action="{{route('activities.branch')}}" >
@csrf

 <select class="form-control input-sm" id="branchselect" name="branch" onchange="this.form.submit()">
  @foreach ($myBranches as $key=>$branch)
    <option {{$branch->id == $key ? 'selected' : ''}} value="{{$key}}">{{$branch}}</option>
  @endforeach 
</select>

</form>
</div>
@endif

@livewire('activities-table', ['branch'=>$branch])
@include('partials._scripts')

@endsection