@extends('site.layouts.maps')
@section('content')


<h1>{{$data['title']}}</h1>
@if(isset($data['listviewref']))
<<<<<<< HEAD
<p><a href="{{$data['listviewref']}}"><i class="fa fa-th-list" aria-hidden="true"></i> List view</a></p>
=======
<p><a href="{{$data['listviewref']}}"><i class="fas fa-th-list" aria-hidden="true"></i> List view</a></p>
>>>>>>> development

@endif
{!! isset($filtered) && $filtered ? "<h4 class='filtered'>Filtered</h4>" : ''!!}
@include('partials.advancedsearch')

@include('maps.partials._form')

@if ($data['type'] == 'branch')

@include('maps.partials._keys')
@endif

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
		  $(function() {
        $('#cp2').colorpicker();
      });
		 
			  
		  
        });
    </script>


<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development

