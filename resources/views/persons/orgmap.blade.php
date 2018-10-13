@extends('site/layouts/maps')
@section('content')
<h2>Sales Team Members</h2>
@include('partials/_showsearchoptions')
@include('partials/advancedsearch')

<p><a href='{{route("person.index")}}'><i class="fas fa-th-list" aria-hidden="true"></i> List view</a></p>

    <div id="map" style="width: 800px; height: 600px"></div>

    <script type="text/javascript">
    //<![CDATA[

    
    function load() {
      var map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(37, -100),
        zoom: 4,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file
      downloadUrl("{{route('salesmap')}}", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");

          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng")));
          var html = "<b>" + name + "</b>";
         
          var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon
          });
          bindInfoWindow(marker, map, infoWindow, html);
        }
      });
    }

    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }

    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
    }

    function doNothing() {}

    //]]>

  </script>

@endsection
