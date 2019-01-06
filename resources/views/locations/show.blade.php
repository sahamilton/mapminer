@extends('site.layouts.default')
@section('content')

<h2>{{$location->businessname}}</h2>
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
      <a class="nav-link active" 
        id="home-tab" 
        data-toggle="tab" 
        href="#home" 
        role="tab" 
        aria-controls="home" 
        aria-selected="true">
        <strong>Location Details</strong>
      </a>


      <a class="nav-link" 
        id="note-tab"
        data-toggle="tab" 
        href="#notes"
        role="tab" 
        aria-controls="notes" 
        aria-selected="true">

        <strong>Location  Notes ({{$location->relatedNotes->count()}})
        </strong>
      </a>

      <a class="nav-link"  
          id="contact-tab"
          data-toggle="tab" 
          href="#contacts"
          role="tab" 
          aria-controls="contacts" 
          aria-selected="true">

        <strong>Location  Contacts ({{$location->contacts->count()}})
        </strong>
      </a>
    </li>
    
      <a class="nav-link" 
          id="watchers-tab" 
          data-toggle="tab" 
          href="#watchers"
          role="tab" 
          aria-controls="watchers" 
          aria-selected="true">

        <strong>Watched By {{$location->watchedBy->count()}}
        </strong>
      </a>
    </li>
    



  </ul>
<?php $type="location";
$id= $location->id;?>
  <div class="tab-content" id="myTabContent">
    <div id="home" class="tab-pane show active">
      @include('locations.partials._tabdetails')
    </div>
    <div id="notes" class="tab-pane fade">
      @include('locations.partials._tabnotes')
    </div>
    <div id="contacts" class="tab-pane fade">
      @include('locations.partials._tabcontacts')
    </div>
    @if($location->watchedBy->count()>0)
    <div id="watchers" class="tab-pane fade">
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
@endsection
