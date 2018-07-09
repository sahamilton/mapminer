@extends('site/layouts/default')
@section('content')
<?php $maxlat=$minlat=$maxlng=$minlng="";?>
<div class="page-header">
<div class="pull-right">
		
		</div>
        
<h3>Region: {{$data['region']->region}} </h3>    
        
    

               
<table class="table table-striped table-bordered">
<thead><tr>
<th>Branch</th>
<th>Address</th>
<th>City State</th>
</tr>
</thead>
<tbody>
@foreach ($data['branches'] as $location)

<tr><td><a href="{{ route('show/branch', $location->id) }}" title='show {{$location->branchname}} locations'>{{$location->branchname}}</a></td>
<td>{{$location->address->street}}</td><td>{{$location->address->city}} {{$location->address->state}}</td>
<?php
if($maxlat =="") {
	$maxlat = $minlat = $location->lat;
	$maxlng = $minlng = $location->lng;
	
}
if($location->lat > $maxlat) {
	$maxlat = $location->lat;
}
if($location->lat < $minlat) {
	$minlat = $location->lat;
}
if(($location->lng) > $maxlng) {
	$maxlng = $location->lng;
}
if(($location->lng) < $minlng) {
	$minlng = $location->lng;
}?>
</tr>
@endforeach
</tbody>
</table>

{{$data['branches']->links()}}
</div>
@stop