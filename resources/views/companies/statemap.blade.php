@extends('site/layouts/maps')
@section('content')


<h2>All {{$data['company']->companyname}} Locations in {{$data['state']}}</h2>
<p><a href="{{ URL::to('company/'. $data['company']->id) }}" title='Show all {{$data['company']->companyname}} Locations'>All {{$data['company']->companyname}} Locations</a></p>
<p><a href='{{URL::to("company/".$data['id']."/state/".$data['statecode'])}}'><i class="glyphicon glyphicon-th-list"></i> List view</a></p>
<div id="store-locator-container">
<?php $data['address'] = "Lat:" .number_format($data['lat'],3) . "  Lng:" .number_format($data['lng'],3) ;
$data['distance'] = Config::get('default_radius');?>
@include('maps/partials/_form')
	<div id="map-container">
        <div id="loc-list"><p></p>

            <ul id="list"></ul>
        </div>
        <div id="map"></div>
      </div>
    </div>

<script>
	  $(function() {
          $('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true, 'defaultLat': '{{$data['lat']}}', 'defaultLng' : '{{$data['lng']}}', 'dataLocation' : '{{ URL::to("api/company/".$data['id']."/statemap/" . $data['statecode'])}}','zoomLevel': 7, 'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-description.html')}}','listTemplatePath' : '{{asset('maps/templates/location-list-description.html')}}'} );
        });
    </script>
   
@stop
