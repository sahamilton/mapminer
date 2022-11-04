@extends('site.layouts.maps')
@section('content')

<h1>{{$data['title']}}</h1>
@if(isset($data['listviewref']))

<p><a href="{{$data['listviewref']}}"><i class="fas fa-th-list" aria-hidden="true"></i> List view</a></p>


@endif
{!! isset($filtered) && $filtered ? "<h4 class='filtered'>Filtered</h4>" : ''!!}
@include('partials.advancedsearch')

@include('maps.partials._form')


<div>

  
   </div>

<div id="store-locator-container">
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
            'defaultLat': '{{$data['lat']}}', 
            'defaultLng' : '{{$data['lng']}}', 
            'dataLocation' : '{{URL::to($data['datalocation'])}}',
            'zoomLevel':{{$data['zoomLevel']}}, 
            'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-description.html')}}','listTemplatePath' : '{{asset('maps/templates/location-list-description.html')}}'} );
      		   $('#cp2').colorpicker();
		    }
     );
   
    </script>


@endsection

