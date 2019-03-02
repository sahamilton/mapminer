@extends('site.layouts.default')
@section('content')
<h2>Branch {{$branch->branchname}}
  @if($activitytype)
    {{ $activitytype->activity}} activities
@else
Activities
@endif
<span class="text text-danger" title="Activities in the past month">*</span></h2>
@if ($activitytype)
<p><a href="{{route('branch.activity',$branch->id)}}">Return to all activities</a></p>
@endif
<p><a href="{{route('opportunities.branch',$branch->id)}}">Return to {{$branch->branchname}} Dashboard</a></p>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Company</th>
      <th>Address</th>
      <th>Activity Date</th>
      <th>Activity Type</th>
      <th>Posted By</th>
      <th>Comment</th>
      <th>Follow Up Date</th>
     </thead>
     <tbody>

     	@foreach ($branch->activities as $activity)

     	<tr>

     		<td><a href="{{route('address.show',$activity->address_id)}}">{{$activity->relatesToAddress->businessname}}</a></td>
     		<td>{{$activity->relatesToAddress->fullAddress()}}</td>
     		
        <td>{{$activity->activity_date->format('Y-m-d')}}</td>

        <td><a href="{{route('branch.activity',['branch'=>$branch->id,'activity'=>$activity->type])}}"
          title="See all branch {{$branch->id}}'s {{$activity->type->activity}} activities" >{{$activity->type->activity}}</a></td>
        <td>{{$activity->user->person->fullName()}}</td>
        <td>{{$activity->note}}</td>
        <td>{{$activity->followup_date ? $activity->followup_date->format('Y-m-d') : ''}}</td>

     		

     	</tr>
     	@endforeach
     </tbody>
 </table>
 <p><span class="text text-danger">*</span> In past month</p>
@include('partials._scripts')
@endsection