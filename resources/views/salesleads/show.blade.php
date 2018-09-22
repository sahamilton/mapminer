@extends('site/layouts/default')
@section('content')

<h2>{{$lead->businessname}}</h2>

<h4>A location of {{$lead->companyname}}</h4>
<p><strong>Lead Ref:</strong>000{{$lead->id}}</p>
<p><a href="{{route('salesleads.index')}}">Return to all leads</a></p>
@if(! $manager)
<div id="{{$lead->id}}" data-rating="{{intval(isset($rank) ? $rank : 0)}}" class="starrr" >
           <strong> Your Rating: </strong></div>
<div class="row">
 
  @if($lead->ownedBy->first()->pivot->status_id == 3)
    <button type="button" class="btn btn-success " disabled>Closed</button>
  @else
  <button type="button" class="btn btn-info " data-toggle="modal" data-target="#myModal">Close Lead</button>
  @endif
</div>
 @else
 <p><a href="{{route('salesleads.index')}}">Return to sales team</a></p>
 @endif
      <div id="map-container">
        <div style="float:left;width:300px">
    <p><strong>Address:</strong> {!! $lead->fullAddress() !!}</p>
    <p><strong>Created:</strong> {{$lead->created_at->format('M j, Y')}}</p>
    <p><strong>Available From:</strong> {{$lead->leadsource->datefrom->format('M j, Y')}}</p>
    <p><strong>Available Until:</strong> {{$lead->leadsource->dateto->format('M j, Y')}}</p>
    <p><strong>Description:</strong> {{$lead->description}}</p>
    <p><strong>PR Customer Number:</strong>{{$lead->cutomer_id}}</p> 
   
    <p><strong>Industry Vertical:</strong><ul>
    @foreach ($lead->vertical as $vertical)
    <li>{{$vertical->filter}}</li>
    @endforeach
    </ul></p>

    <p><strong>Primary Contact:</strong>{{$lead->contact}}</p>
    <p><strong>Phone:</strong>{{$lead->phone}}</p>

<hr />
<h2>Notes</h2>

@foreach ($lead->relatedNotes as $note)
<p>{{date_format($note->created_at,'m-d-Y')}}...<em>{{$note->note}}</em><br />
 -- @if(isset($note->writtenBy->person)) {{$note->writtenBy->person->postName()}} @endif</p>
@if($note->user_id == Auth::user()->id  or Auth::user()->hasRole('Admin'))
<br /><a href="{{route('notes.edit',$note->id)}}" title="Edit this note"><i class="fa fa-pencil" aria-hidden="true"></i></a> | 

<a data-href="{{route('notes.destroy',$note->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = " this prospect note" href="#">
  <i class="fa fa-trash-o" aria-hidden="true"> </i></a>
<hr />
@endif


</p>

@endforeach
<form method="post" action="{{route('notes.store')}}" name="notesform" >
{{csrf_field()}}

<div>
<label for 'note'>Add a Note:</label>
<div>
<!-- note -->


                <input type="text" required class="form-control" name='note' description="note" value="{{ old('note') ? old('note') : isset($data->note) ? $data->note : "" }}" placeholder="note">
                <span class="help-block">
                    <strong>{{ $errors->has('note') ? $errors->first('note') : ''}}</strong>
                    </span>


    
{{ $errors->first('note') }}
</div></div>
<input type="hidden" name="type" value="lead" />
<input type="hidden" name="related_id" value="{{$lead->id}}" />

<button type="submit" class="btn btn-success">Add New Note</button>
</form>
</div>
     <div id="map" style="height:300px;width:500px;border:red solid 1px">
</div>
</div>

<div id="notes" style="clear:both">



</div>

@include('partials._modal')
@include ('leads.partials._closeleadform')
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
