@extends('site/layouts/default')
@section('content')

<h2>{{$location->businessname}}</h2>


      <div id="map-container">
        <div style="float:left;width:300px">
<p><strong>Vertical: </strong>{{isset($location->company->industryVertical->filter) ? $location->company->industryVertical->filter : 'Not Specified'}}</p>
<p><strong>Segment: </strong>{{isset($location->verticalsegment->filter) ? $location->verticalsegment->filter  : 'Not Specified'}}</p>
<p><strong>Business Type:</strong> {{isset($location->clienttype->filter) ? $location->clienttype->filter : 'Not Specified'}}
<p><i>A location of <a href="{{ route('company.show', $location->company->id) }}" title='show all locations of {{$location->company->companyname}} national account'>{{$location->company->companyname}}</a></i><br />
@if(isset($company->managedBy->firstname))
Account managed by <a href="{{route('person.show',$location->company->managedBy->id)}}" title="See all accounts managed by {{$location->company->managedBy->firstname.' '.$location->company->managedBy->lastname}}">{{$location->company->managedBy->firstname.' '.$location->company->managedBy->lastname}}</a>

</i></p>
@endif
<a href="{{route('salesnotes',$location->company->id)}}" title="Read notes on selling to {{$location->company->companyname}}"><i class="glyphicon glyphicon-search"></i>  Read 'How to Sell to {{$location->company->companyname}}' </a>
<p><b>Address:</b><br/>{{$location->street}}<br />  {{$location->city}}  {{$location->state}} {{$location->zip}}</p>
<p><b>Primary Contact:</b>{{$location->contact}}</p>
<p><b>Phone:</b>{{$location->phone}}</p>
@if(isset($watch->location_id))

<p><a href="/watch/delete/{{$watch->id}}" title="Remove this location to my watch list"><i class="glyphicon glyphicon-floppy-remove"></i> Remove from My Watch List</a>

@else
<p><a href="/watch/add/{{$location->id}}" title="Add this location to my watch list"><i class="glyphicon glyphicon-floppy-disk"></i> Add to My Watch List</a></i>
@endif

<?php if(isset($branch[0]->branchid)){?>
<p>Closest Branch: <a href="{{ route('branches.show', $branch[0]->branchid) }}" title='show all {{trim($branch[0]->branchname)}} national accounts'>{{$branch[0]->branchnumber}}:{{$branch[0]->branchname}} </a></p>
<?php }else{?>
<p>Closest Branch: <a href="{{ route('assign.location', $location->id) }}" title=''>Closest Branch</a></p>

<?php } ?>
<p> <a href="{{ route('assign.location', $location->id) }}" title='See nearby branches'>Other Nearby Branches</a></p>
<a href="/location/{{$location->id}}/edit" title="Edit this location"><i class="glyphicon glyphicon-pencil"></i>Edit location</a>
<hr />
<h2>Notes</h2>

@foreach ($location->relatedNotes as $note)
<p>{{date_format($note->created_at,'m-d-Y')}}...<em>{{$note->note}}</em><br />
 -- {{$note->writtenBy->person->firstname}} {{$note->writtenBy->person->lastname}}</p>
@if($note->user_id == Auth::user()->id  or Auth::user()->hasRole('Admin'))
<br /><a href="{{route('notes.edit',$note->id)}}" title="Edit this note"><i class="glyphicon glyphicon-pencil"></i></a> | 
<a href="{{route('delete/note',$note->id)}}" onclick="if(!confirm('Are you sure to delete this note?')){return false;};" title="Delete this note"><i class="glyphicon glyphicon-trash"></i></a>
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
{{Form::hidden('location_id',$location->id)}}
<button type="submit" class="btn btn-success">Add New Note</button>
{{Form::close()}}
</div>
     <div id="map" style="height:300px;width:500px;border:red solid 1px"/>
</div>
</div>

<div id="notes" style="clear:both">



</div>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key={{config('maps.api_key')}}"></script>

<script type="text/javascript">
function initialize() {
  var myLatlng = new google.maps.LatLng({{$location->lat}},{{$location->lng}});
  var mapOptions = {
    zoom: 14,
    center: myLatlng
  }
  var infoWindow = new google.maps.InfoWindow;
  
  var map = new google.maps.Map(document.getElementById('map'), mapOptions);
	var name = "{{$location->company->companyname}}";
    var address = "{{$location->street}}" + " {{$location->city}}" + " {{$location->state}}" + " {{$location->zip}}";
    var html = "<a href='{{route('company.show' , $location->company->id) }}'>" + name + "</a> <br/>" + address;
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
