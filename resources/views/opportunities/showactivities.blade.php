@extends('site.layouts.default')
@section('content')
<h2>Branch {{$branch->branchname}}
{{ $activitytype->activity}} activities</h2>
<p><a href="{{route('opportunities.branch',$branch->id)}}">Return to {{$branch->branchname}} Dashboard</a></p>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
      <th>Business</th>
      <th>Address</th>
      <th>Activity Date</th>
     </thead>
     <tbody>

     	@foreach ($addresses as $location)
     	<tr>

     		<td><a href="{{route('address.show',$location->id)}}">{{$location->businessname}}</a></td>
     		<td>{{$location->fullAddress()}}</td>
     		<td>
     			@foreach ($location->activities as $siteactivity)
     			
     				@if($siteactivity->activitytype_id == $activitytype->id)
     					<li>{{$siteactivity->activity_date->format('Y-m-d')}}</li>
     				@endif
     			@endforeach
     		</td>
     	</tr>
     	@endforeach
     </tbody>
 </table>
@include('partials._scripts')
@endsection