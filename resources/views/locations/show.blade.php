@extends('site.layouts.default')
@section('content')

<h2>{{$location->businessname}}</h2>


<ul class="nav nav-tabs">
    <li class="active">
      <a data-toggle="tab" href="#project">
        <strong>Location Details</strong>
      </a>
    </li>

    <li>
      <a data-toggle="tab" href="#notes">
        <strong>Location  Notes ({{$location->relatedNotes->count()}})
        </strong>
      </a>
    </li>
    
    <li>
      <a data-toggle="tab" href="#contacts">
        <strong>Location  Contacts ({{$location->contacts->count()}})
        </strong>
      </a>
    </li>
    
    <li>
      <a data-toggle="tab" href="#watchers">
        <strong>Watched By {{$location->watchedBy->count()}}
        </strong>
      </a>
    </li>
    



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
    <div id="contacts" class="tab-pane fade in">
      @include('locations.partials._tabcontacts')
    </div>
    @if($location->watchedBy->count()>0)
    <div id="watchers" class="tab-pane fade in">
      @include('locations.partials._tabwatchers')
    </div>
    @endif
   

  </div>





</div>


<div id="notes" style="clear:both">



</div>


@include('locations.partials.map')


@include('partials._modal')
@include('partials._locationcontactmodal')
@include('partials._scripts');
@stop
