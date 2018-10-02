@extends('admin.layouts.default')
@section('content')
<h2>Confirm these recipients</h2>
<form method="post" action="{{route('branchassignments.send')}}" >
  @csrf
  <input type="submit" name ="submit" value="Send Emails to Selected" class="btn btn-info" />
<table class="table" id='nosorttable'>
<thead>
  <th><input type="checkbox" checked id="checkAll"></th>
  <th>Recipient</th>
  <th>Role</th>
  <th>Assigned Branches</th>
  <th>Last Confirmed</th>
</thead>
<tbody>
@foreach($recipients as $recipient)
<tr>
  <td><input checked type="checkbox" name="id[]" value="{{$recipient->id}}" /></td>
  <td>{{$recipient->fullName()}}</td>
  <td>@foreach($recipient->userdetails->roles as $role)
      {{$role->name}}
      @endforeach
  </td>
  <td>
    {{$recipient->branchesServiced->count()}}
  </td>
  <td>@if($recipient->lastUpdatedBranches()->first()->lastdate)
    {{$recipient->lastUpdatedBranches()->first()->lastdate}}
  @endif</td>
</tr>
@endforeach
</tbody>
</table>
<input type="hidden" name="test" value="{{$test}}" >
</form>
<script>
  $("#checkAll").click(function () {
     $('input:checkbox').not(this).prop('checked', this.checked);
 });
</script>
@include('partials._scripts')
@endsection