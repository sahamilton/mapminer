@extends('site.layouts.default')
@section('content')

<h2>{{$location->businessname}}</h2>


<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#project"><strong>Location Details</strong></a></li>

    <li><a data-toggle="tab" href="#notes"><strong>Location  Notes @if(count($location->relatedNotes)>0) ({{count($location->relatedNotes)}}) @endif</strong></a></li>



  </ul>
<?php $type="location";
$id= $location->id;?>
  <div class="tab-content">
    <div id="project" class="tab-pane fade in active">
      @include('locations.partials._tabdetails')
    </div>
    <div id="notes" class="tab-pane fade in">
      @include('locations.partials._tabnotes')
    </div>
   
  </div>





</div>


<div id="notes" style="clear:both">



</div>


@include('locations.partials.map')


@include('partials._modal')
@include('partials._scripts');
@stop
