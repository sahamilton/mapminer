@extends('site.layouts.default')
@section('content')
<style>
.highlight { background-color: AliceBlue; }
</style>
<h2>Branch Associations for {{$details->fullName()}}</h2>
<h4>Current Role:
	@foreach ($details->userdetails->roles as $role)
	{{$role->display_name}}
	@endforeach
</h4>

<form class="form" 
    action="{{route('branchassignments.update',$details->user_id)}}" 
    method="post" 
    name="branchassignment">
@method('put')
@csrf
<div class="card-body">
    
@if($branches->count() >0)
  <h5 class="card-title alert alert-info"><strong>Update Current Assignments</strong></h5>
      <p class="card-text">If your branch assignments are incomplete or incorrect, simply uncheck the appropriate branches in the list and / or add any missing in box below. Note that you cannot remove a branch if the current branch manager is part of your team.</p>
  <h6>Last Updated: {{$branches->first()->branchteam->first()->pivot->updated_at}}</h6>
  <table class="table table-bordered table-condensed">
    <thead>
      <th>Branch</th>
      <th>Branch #</th>
      <th>Address</th>
      <th>Manager(s)</th>
      <th>Distance</th>
      <th>Assigned</th>

    </thead>
  <tbody>

  @foreach($branches as $branch)
  @include('branchassignments.partials._selectbranch')
   @endforeach
  </tbody>
   </table>
@else
  <div class="alert alert-warning">
    <p>You currently are not assigned to any branches in Mapminer. Check / uncheck any of the nearby branches to assign yourself.</p>
  </div>
  <div class="list-group-item">
    
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

<script>
$( document ).ready(function() {
    $("input[id^=branch]").change (function () {
     changeBackground();
    var id = $(this).val();
      $.ajax(
        {
            type: "get",
            cache: false,
            url: '{{route("branchassignment.change",$details->user_id)}}',
            data: {id: id,api_token:"{{auth()->user()->api_token}}"},
            dataType: "xml",
            contentType: "json",
            success: true
        }); 
    });

    function changeBackground(){
      $(".item").each(function () {
          //Check if the checkbox is checked
          if ($(this).closest('tr').find("input[id^=branch]").is(':checked')) {
              $(this).closest("tr").addClass("highlight");
          }else{
             $(this).closest("tr").removeClass("highlight");

          }

      });
  };
});

</script>

@endsection
