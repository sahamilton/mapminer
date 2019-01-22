@extends('admin.layouts.default')
@section('content')
@php 
$expiration = [1,2,5,7,14];
@endphp
<h2>Confirm these recipients</h2>
<form method="post" action="{{route('branchassignments.send')}}" >
  @csrf

<div class="form-row">
  <div class="form-group inline{{ $errors->has('days') ? ' has-error' : '' }}">
    <label for="days" class="mr-2 col-form-label-sm"><strong>Expiration days:</strong></label>
    <select class="form-control form-control-sm" required name="days">
      @foreach ($expiration as $day)
      <option @if($day ==2 ) selected @endif value="{{$day}}">{{$day}}</option>
      @endforeach
    </select>
</div>
</div>
  <input type="submit" name ="submit" value="Send Emails to Selected" class="btn btn-info" />
<table class="table" id='nosorttable'>
<thead>
  <th><input type="checkbox" checked id="checkAll"></th>
  <th>Recipient</th>
  <th>Role</th>
  <th>Assigned Branches</th>
  <th>Servicelines</th>
  <th>Last Confirmed</th>
</thead>
<tbody>
@foreach($recipients as $recipient)
<tr>
  <td><input checked type="checkbox" name="id[]" value="{{$recipient->id}}" /></td>
  <td>{{$recipient->fullName()}}</td>
  <td>@foreach($recipient->userdetails->roles as $role)
      {{$role->displayName}}
      @endforeach
  </td>
  <td>{{$recipient->branchesServiced->count()}}</td>
  <td>
    @foreach ($recipient->userdetails->serviceline as $serviceline)
      <li>{{$serviceline->ServiceLine}}</li>
    @endforeach
  </td>
  <td>@if($recipient->lastUpdatedBranches()->first()->lastdate)
    {{$recipient->lastUpdatedBranches()->first()->lastdate}}
  @endif</td>
</tr>
@endforeach
</tbody>
</table>
<input type="hidden" name="test" value="{{$test}}" >
<input type="hidden" name="campaign_id" value="{{$campaign->id}}" >

</form>
<script>
  $("#checkAll").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
</script>
@include('partials._scripts')
@endsection