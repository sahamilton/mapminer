@extends('site/layouts/default')
@section('content')

<h4> Create New Location for {{ $location->companyname }} </h4>
<div class="container">

<?php
$buttonLabel ='Create Location';
$companyid = $location->id;
$ultimateDUNS = $location->DUNS;?>
{{Form::open(['route'=>'locations.store'])}}
@include('locations/partials/_form')
{{Form::close()}}
</div>
</div>
@stop