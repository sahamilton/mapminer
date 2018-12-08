@extends('site/layouts/default')
@section('content')
<h4> Edit This {{ $location->company->companyname }} Location</h4>

<?php 
$buttonLabel = 'Edit Location';
$companyid = $location->company->id;
$ultimateDUNS = $location->company->DUNS;

?>
{{Form::model($location, ['method'=>'PATCH','route'=>['locations.update', $location->id]]) }}
@include('locations/partials/_form')
{{Form::close()}}
</div>
</div>
@endsection
