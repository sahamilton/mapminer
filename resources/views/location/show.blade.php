@extends('site.layouts.default')
@section('content')

<h2>{{$location->location->businessname}}</h2>

<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><strong>Location Details</strong></a>
  </li>

    


    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#notes">

        <strong>Location  Notes ({{$location->location->relatedNotes->count()}})
        </strong>
      </a>
    </li>
    

    <li class="nav-item">
      <a class="nav-link"  data-toggle="tab" href="#contacts">

        <strong>Location  Contacts ({{$location->location->contacts->count()}})
        </strong>
      </a>
    </li>
    

    <li class="nav-item">
      <a class="nav-link"  data-toggle="tab" href="#watchers">

        <strong>Watched By {{$location->location->watchedBy->count()}}
        </strong>
      </a>
    </li>
    



  </ul>
<?php $type="location";
$id= $location->location->id;?>
  <div class="tab-content" id="myTabContent">
    <div id="project" class="tab-pane show active">
      @include('location.partials._tabdetails')
    </div>
    <div id="notes" class="tab-pane fade">
      @include('location.partials._tabnotes')
    </div>
    <div id="contacts" class="tab-pane fade">
      @include('location.partials._tabcontacts')
    </div>
    @if($location->location->watchedBy->count()>0)
    <div id="watchers" class="tab-pane fade">
      @include('location.partials._tabwatchers')
    </div>
    @endif
   

  </div>





</div>


<div id="notes" style="clear:both">



</div>


@include('location.partials.map')


@include('partials._modal')
@include('partials._locationcontactmodal')
@include('partials._scripts');
@endsection
