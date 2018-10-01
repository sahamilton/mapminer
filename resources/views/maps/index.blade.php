@extends('site.layouts.maps')
@section('content')

 <div id="store-locator-container">

      <div id="page-header">
	  <?php 

$type = $data['type'];	 
if($type =='branch'){
	$datalocation = "api/mylocalbranches/";
	$label = 'branches';
	$switch = 'account';
	$switchlabel = 'accounts';
	

}else{
	$datalocation ="api/mylocalaccounts";
	$label = 'national account locations';
	$switch='branch';
	$switchlabel = 'branches';	
}
?>

 
      <?php $values = Config::get('app.search_radius');?>
      <form action="{{route('maps',trim($data['type']))}}" method="get" />
      <h4>Showing {{$label}} within
       <select name='d' class="btn btn-mini" onchange='this.form.submit()'>
           @foreach($values as $value)
           	@if($value == $data['distance'])
            	<option selected value="{{$value}}">{{$value}} miles</option>
                @else
           		<option value="{{$value}}">{{$value}} miles</option>
                @endif
           @endforeach
        </select> of your location</h4>
        <noscript><input type="submit" value="Submit"></noscript>
</form>
<p>
<p><a href = "{{route('lists')}}?t={{$type}}&d={{$data['distance']}}"><i class="fas fa-th-list" aria-hidden="true"></i> Show List View</a></p>

<p>Show nearby <a href="{{route('maps')}}?t={{$switch}}&d={{$data['distance']}}" title="Show {{$switchlabel}} within {{$data['distance']}} miles">{{$switchlabel}}</a></p>
      </div>
      <div id="map-container">
        <div id="loc-list"><p></p>

            <ul id="list"></ul>
        </div>
        <div id="map"></div>
      </div>
    </div>

    <script>
	
	  $(function() {
		
          $('#map-container').storeLocator({'slideMap' : false, 'autoGeocode':true, 'dataLocation' : '{{ URL::to($datalocation) }}/{{$data['distance']}}','zoomLevel': 9, 'infowindowTemplatePath' : '{{asset('maps/templates/infowindow-description.html')}}','listTemplatePath' : '{{asset('maps/templates/location-list-description.html' )}}'} );
        });
    </script>
@endsection