@extends('site/layouts/default')
@section('content')
<h4> Edit This {{ $location->company->companyname }} Location</h4>

@php
    $buttonLabel = 'Edit Location';
    $companyid = $location->company->id;
    $ultimateDUNS = $location->company->DUNS;
@endphp
<form 
name="locations"
method="post"
action="{{route('locations.update', $location->id)}}">
@csrf
@method="patch"


@include('locations/partials/_form')
</form>
</div>
</div>
@endsection
