@extends('site.layouts.mobile')
@section('content')
<div class="container">
<h4>Create a New Lead at {{$lead->street}}</h4>
<p><a href="{{route('mobile.index')}}">Return to mobile view</a></p>
<div class="col-md-5">
    <form class="form-horizontal">
@include('mobile.partials._leadform')
</form>
</div>
@if($results->count()>0)

<h4>Existing Leads</h4>
@include('mobile.partials._leads')


@endif

</div>
@endsection