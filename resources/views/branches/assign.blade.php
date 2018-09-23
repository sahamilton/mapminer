@extends('site/layouts/default')
@section('content')
<div class="page-header">
<div class="pull-right">
		
		</div>
<h2>Nearby Branches</h2>
<p>The closest branches that can serve the 

<a href="{{route('locations.show',$data['location']->id)}}">{{$data['location']->businessname}} </a>
location in {{$data['location']->city}}, {{$data['location']->state}} are:<p>
<p><a href='{{route("nearby.location",$data['location']->id)}}'>
  <i class="fa fa-flag" aria-hidden="true"></i> Map view</a></p>
<table class="table table-striped table-bordered table-condensed">
<thead>
<th>Branch</th>
<th>Branch #</th>
<th>Address</th>
<th>City</th>
<th>State</th>
<th>Distance</th>
</thead>
<tbody>

@foreach($data['branch'] as $branch)
<tr>

    <td><a href="{{route('branches.show',$branch->id)}}" title="Review {{trim($branch->branchname)}} branch">{{$branch->branchname}}</a></td>
    <td>{{$branch->id}}</td>
    <td>{{$branch->street}}</td>
    <td>{{$branch->city}}</td>
    <td>{{$branch->state}}</td>
    <td>{{number_format($branch->distance,2)}} miles away.</td>

   </tr>
 @endforeach
 </table>
</p>
</div>
    
@endsection
