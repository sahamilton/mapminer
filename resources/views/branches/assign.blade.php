@extends('site/layouts/default')
@section('content')
<div class="page-header">
<div class="pull-right">
		
		</div>
<h2>Nearby Branches</h2>
<p>The closest branches that can serve the 


<a href="{{route('location.show',$data['location']['id'])}}">{{$data['location']['businessname']}} </a>

location in {{$data['location']['city']}} are:<p>
<p><a href='{{route("nearby.location",$data['location']['id'])}}'>
  <i class="glyphicon glyphicon-flag"></i> Map view</a></p>
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
@for ($i = 0; $i < count($data['branch']); $i++)
<tr>
	
    <td><a href="{{route('branch.show',$data['branch'][$i]['branchid'])}}" title="Review {{trim($data['branch'][$i]['branchname'])}} branch">{{$data['branch'][$i]['branchname']}}</a></td>
    <td>{{$data['branch'][$i]['branchnumber']}}</td>
   
    <td>{{$data['branch'][$i]['street']}}</td>
    <td>{{$data['branch'][$i]['city']}}</td>
    <td>{{$data['branch'][$i]['state']}}</td>
    <td>{{number_format($data['branch'][$i]['distance_in_mi'],2)}} miles away.</td>

   </tr>
 @endfor
 </table>
</p>
</div>
    
@stop
