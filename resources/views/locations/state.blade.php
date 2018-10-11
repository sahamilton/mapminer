@extends('site/layouts/default')
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('maps/css/map.css')}}">
 

<link rel="stylesheet" href="{{asset('maps/css/map.css')}}">
<div class="page-header">
<div class="pull-right">
	
		</div>
        <h1>National Accounts</h1>
       {!!$filtered ? "<h4 class='filtered'>Filtered</h4>" : ''!!}
        <h4> closest to the branch</h4>        
		</div>
        <?php $data['address'] = "Lat:" .number_format($data['lat'],3) . "  Lng:" .number_format($data['lng'],3) ;
$data['distance'] = '100';?>
@include('maps/partials/_form')
@include('partials/advancedsearch')
         <div id="form-container">
        <form id="user-location" method="post" action="#">
            <div id="form-input">
              <label for="address">Enter Address or Zip Code:</label>
              <input type="text" id="address" name="address" />
             </div>
            
            <button id="submit" type="submit">Submit</button>
        </form>
      </div>
 <div id="store-locator-container">
      <div id="page-header">

      </div>
      
      

      <div id="map-container">
        <div id="loc-list"><p></p>

            <ul id="list"></ul>
        </div>
        <div id="map"></div>
      </div>
    </div>

    <script src="//code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="{{asset('maps/js/handlebars-1.0.0.min.js')}}"></script>
    <script src="https://maps.google.com/maps/api/js?client={{config('maps.api_key')}}&sensor=false"></script>
    <script src="{{asset('maps/js/jquery.storelocator.js')}}"></script>
    <script>
        $(function() {
          $('#map-container').storeLocator({'slideMap' : false });
        });
      </script>
	
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development
