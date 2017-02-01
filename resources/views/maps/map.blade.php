@extends('site.layouts.maps')


@if ($data['type'] == 'branch')
	<?php $fields = array('Branch Name'=>'branchname','Address'=>'street','City'=>'city','State'=>'state','ZIP'=>'zip','Miles'=>'distance_in_mi');?>
	


@else
<?php $fields = array('Business Name'=>'businessname','National Acct'=>'companyname','Address'=>'street','City'=>'city','State'=>'state','ZIP'=>'zip','Miles'=>'distance_in_mi'); 
?>

@endif
@section('content')


<h1>{{$data['title']}}</h1>

{{$filtered ? "<h4 class='filtered'>Filtered</h4>" : ''}}
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
          $('#map-container').storeLocator({'slideMap' : false, 'defaultLoc': true, 'defaultLat': '{{$data['lat']}}', 'defaultLng' : '{{$data['lng']}}', 'dataLocation' : '{{URL::to($data['urllocation'] . '/'. $data['distance'].'/'.$data['latlng'].'/'.$data['company'])}}','zoomLevel':{{$data['zoomLevel']}}, 'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-description.html')}}','listTemplatePath' : '{{asset('maps/templates/location-list-description.html')}}'} );
		  $(function() {
        $('#cp2').colorpicker();
      });
		 
			  
		  
        });
    </script>


@stop

