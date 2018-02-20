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
        <strong>Location  Notes @if(count($location->relatedNotes)>0) ({{count($location->relatedNotes)}}) @endif
        </strong>
      </a>
    </li>
    
    <li>
      <a data-toggle="tab" href="#contacts">
        <strong>Location  Contacts @if(count($location->contacts)>0) ({{count($location->contacts)}}) @endif
        </strong>
      </a>
    </li>
    @if(count($location->watchedBy)>0)
    <li>
      <a data-toggle="tab" href="#watchers">
        <strong>Watched By {{count($location->watchedBy)}}
        </strong>
      </a>
    </li>
    @endif



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
    @if(count($location->watchedBy)>0)
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
