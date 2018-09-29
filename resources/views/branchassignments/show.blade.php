@extends('site.layouts.default')
@section('content')

<h2>Branch Associations for {{$details->fullName()}}</h2>
<h4>Current Role:
	@foreach ($details->userdetails->roles as $role)
	{{$role->name}}
	@endforeach
</h4>

<form class="form" 
    action="{{route('branchassignments.update',$details->user_id)}}" 
    method="post" 
    name="branchassignment">
@method('put')
@csrf
<div class="card-body">
    

@if($details->branchesServiced()->exists())
<h5 class="card-title alert alert-info"><strong>Update Current Assignments</strong></h5>
    <p class="card-text">If your branch assignments are incomplete or incorrect, simply uncheck the appropriate branches in the list and / or add any missing in box below. </p>
<h6>Last Updated: {{$details->branchesServiced[0]->pivot->updated_at}}</h6>
<table class="table table-striped table-bordered table-condensed">
<thead>
<th>Branch</th>
<th>Branch #</th>
<th>Address</th>
<th>City</th>
<th>State</th>
<th></th>

</thead>
<tbody>

@foreach($details->branchesServiced as $branch)
@include('branchassignments.partials._selectbranch')
 @endforeach
</tbody>
 </table>
@else
<div class="alert alert-warning">
  <p>You currently are not assigned to any branches in Mapminer. Check / uncheck any of the nearby branches to assign yourself.</p>
</div>
<div class="list-group-item">
  <p><strong>Closest Branches to your location</strong></p>
  <div class="row">
    <div class="list-group-item-text col-sm-12">
      <table class="table">
      @foreach($branches as $branch)
        @include('branchassignments.partials._selectbranch')
      @endforeach
      </table>
    </div>
  </div>
</div>
@endif
<div class="alert alert-info">
  <p>Add missing branches by entering the correct branch numbers <em>(4 characters each)</em>, separated by commas and then click update.</p></div>
<div class="form-group{{ $errors->has('id') ? ' has-error' : '' }}">
  <label class="col-md-4 control-label"><strong>Add Branches:</strong></label>
  <div class="form-group">
    <textarea class="form-control col-md-8" name="branches"
    placeholder="branch ids separated by commas">{{ old('branches')}}</textarea>

    <input type="submit" 
    name="submit" 
    class="btn btn-success" 
    value="Update" />
    <span class="help-block">
      <strong>{{ $errors->has('branches') ? $errors->first('branches') : ''}}</strong>
    </span>
  </div>
  </div>
  </div>
</div>



@endsection
