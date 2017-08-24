@extends('site.layouts.default')
@section('content')

<h2>{{$location->businessname}}</h2>


<ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#project"><strong>Location Details</strong></a></li>

    <li><a data-toggle="tab" href="#notes"><strong>Location  Notes</strong></a></li>



  </ul>

  <div class="tab-content">
    <div id="project" class="tab-pane fade in active">
      @include('locations.partials._tabdetails')
    </div>
    <div id="notes" class="tab-pane fade in">
      @include('locations.partials._tabnotes')
    </div>
   
  </div>


@foreach ($location->relatedNotes as $note)
<p>{{date_format($note->created_at,'m-d-Y')}}...<em>{{$note->note}}</em><br />
 -- 
 @if(isset($note->writtenBy->person))
 {{$note->writtenBy->person->postName()}}
 @else
 No longer with company
 @endif
 </p>

@if($note->user_id == Auth::user()->id  or Auth::user()->hasRole('Admin'))
<br /><a href="{{route('notes.edit',$note->id)}}" title="Edit this note"><i class="glyphicon glyphicon-pencil"></i> </a> | 
<a data-href="{{route('notes.destroy',$note->id)}}" 
            data-toggle="modal" 
            data-target="#confirm-delete" 
            data-title = "note"  
            title="Delete this note"
            href="#">
            <i class="fa fa-trash-o" aria-hidden="true"> </i> </a>
           


<hr />
@endif


</p>

@endforeach
<?php $type="location";
$id= $location->id;?>
@include('notes.partials._form')
</div>
     <div id="map" style="height:300px;width:500px;border:red solid 1px"/>
</div>
</div>

<div id="notes" style="clear:both">



</div>


@include('locations.partials.map')


@include('partials._modal')
@include('partials._scripts');
@stop
