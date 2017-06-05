@extends('site/layouts/default')
@section('content')

<h1>{{$title }} {{$leads->firstname}} {{$leads->lastname}}</h1>
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home"><strong>Owned Leads</strong></a></li>
  <li><a data-toggle="tab" href="#menu1"><strong>Offered Leads</strong></a></li>
  

</ul>
<div class="tab-content">
@include('salesleads.partials._ownedleads')

@include('salesleads.partials._offeredleads')
</div>




@include('partials/_scripts')



@stop