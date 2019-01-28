@extends('site.layouts.maps')
@section('content')
<?php $type='map';?>
<h2>Locations near to Branch {{$data['branch']->branchname}}</h2>
<p>{{$data['branch']->fullAddress()}}</p>
@php $data['type']='branch'; @endphp
@include('maps.partials._form')
<p><a href="{{route('showlist.locations',$data['branch']->id)}}"><i class="fas fa-th-list" aria-hidden="true"></i> List view</a></p>
        <div id="store-locator-container" style="z-index: -1 important!;">
            <div id="map-container">
                <div id="loc-list"><p></p>

                    <ul id="list"></ul>
                </div>
                <div id="map"></div>
        </div>
    </div>

         <script>
            $(function() {
            $('#map-container').storeLocator({'slideMap' : false, 
            'defaultLoc': true, 
            'defaultLat': '{{$data['branch']->lat}}', 
            'defaultLng' : '{{$data['branch']->lng}}',
            'dataLocation' :  '{{URL::to($data['urllocation'].'/'.$data['distance'].'/'.$data['latlng'].'/'.$data['company'])}}', 
            'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-description.html')}}'
            ,'listTemplatePath' : '{{asset('maps/templates/location-list-description.html')}}'} );


            });
        </script>

@endsection
