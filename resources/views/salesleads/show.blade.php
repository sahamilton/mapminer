@extends('site/layouts/default')
@section('content')

<h2>{{$lead->businessname}}</h2>

<h4>A location of {{$lead->companyname}}</h4>
@if(! $manager)
<div id="{{$lead->id}}" data-rating="{{intval(isset($rank) ? $rank : 0)}}" class="starrr" >
           <strong> Your Rating: </strong></div>
 <div class="row"><button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Close Lead</button></div>
 @else
 <p><a href="{{route('salesleads.index')}}">Return to sales team</a></p>
 @endif
      <div id="map-container">
        <div style="float:left;width:300px">
    <p><strong>Address:</strong> {{$lead->fullAddress()}}</p>
    <p><strong>Created:</strong> {{$lead->created_at->format('M j, Y')}}</p>
    <p><strong>Available From:</strong> {{$lead->datefrom->format('M j, Y')}}</p>
    <p><strong>Available Until:</strong> {{$lead->dateto->format('M j, Y')}}</p>
    <p><strong>Description:</strong> {{$lead->description}}</p>
   
    <p><strong>Industry Vertical:</strong></p>

    <p><strong>Primary Contact:</strong>{{$lead->contact}}</p>
    <p><strong>Phone:</strong>{{$lead->phone}}</p>

<hr />
<h2>Notes</h2>

@foreach ($lead->relatedNotes as $note)
<p>{{date_format($note->created_at,'m-d-Y')}}...<em>{{$note->note}}</em><br />
 -- {{$note->writtenBy->person->firstname}} {{$note->writtenBy->person->lastname}}</p>
@if($note->user_id == Auth::user()->id  or Auth::user()->hasRole('Admin'))
<br /><a href="{{route('notes.edit',$note->id)}}?lead={{$lead->id}}" title="Edit this note"><i class="glyphicon glyphicon-pencil"></i></a> | 
<a href="{{route('delete/note',$note->id)}}?lead={{$lead->id}}" onclick="if(!confirm('Are you sure to delete this note?')){return false;};" title="Delete this note"><i class="glyphicon glyphicon-trash"></i></a>
<hr />
@endif


</p>

@endforeach

{{Form::open(['route'=>'notes.store'])}}
<div>
{{Form::label('note','Add a Note:')}}
<div>
{{Form::textarea('note')}}
{{ $errors->first('note') }}
</div></div>
{{Form::hidden('lead_id',$lead->id)}}
<button type="submit" class="btn btn-success">Add New Note</button>
{{Form::close()}}
</div>
     <div id="map" style="height:300px;width:500px;border:red solid 1px"/>
</div>
</div>

<div id="notes" style="clear:both">



</div>


@include ('salesleads.partials._closeleadform')
@include('salesleads.partials._scripts')
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{config('maps.api_key')}}"></script>

<script type="text/javascript">
function initialize() {
  var myLatlng = new google.maps.LatLng({{$lead->lat}},{{$lead->lng}});
  var mapOptions = {
    zoom: 14,
    center: myLatlng
  }
  var infoWindow = new google.maps.InfoWindow;
  
  var map = new google.maps.Map(document.getElementById('map'), mapOptions);
  var name = "{{$lead->companyname}}";
    var address = "{{$lead->fullAddress()}}";
    var html =  name +  "<br/>" + address;
  var marker = new google.maps.Marker({
    position: myLatlng,
    map: map,
    title: name,
    clickable: true
  });
   bindInfoWindow(marker, map, infoWindow, html);
}
function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }
google.maps.event.addDomListener(window, 'load', initialize);

    </script>

@stop
