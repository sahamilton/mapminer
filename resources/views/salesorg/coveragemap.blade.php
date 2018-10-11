@extends('site/layouts/maps')
@section('content')
<h2>Branch Sales Coverage</h2>

<<<<<<< HEAD
<p><a href='{{route("branches.index")}}'><i class="fa fa-th-list" aria-hidden="true"></i> List view</a></p>
=======
<p><a href='{{route("branches.index")}}'><i class="fas fa-th-list" aria-hidden="true"></i> List view</a></p>
>>>>>>> development

@include('salesorg/_keys')

  <body onLoad="load()">
    <div id="map" style="width: 800px; height: 600px"></div>

    <script type="text/javascript">
    //<![CDATA[

    var customIcons = {
      '7': {
        icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
      },
      '6': {
        icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
      },
      '5': {
        icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
      },
      '4': {
        icon: 'https://maps.google.com/mapfiles/ms/icons/green-dot.png'
      },
    '3': {
        icon: 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png'
      },

      '2': {
        icon: 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png'
      },
      '1': {
        icon: 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png'
      },

    '0':{
       icon: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
      }
    };

    function load() {
      var map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(39.5,-98.4),
        zoom: 4,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file
      downloadUrl("/uploads/salescoverage.xml", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");
          var address = markers[i].getAttribute("address");
          var brand = markers[i].getAttribute("brand");
          var color = markers[i].getAttribute("color");
          var salescoverage = markers[i].getAttribute("salesreps");
    		  var branchlink = markers[i].getAttribute("locationweb");
    		  var linktitle = "Review the " + name + " branch";
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng"))); 
          var html = "<b><a href='" + branchlink + "' title ='" +linktitle + "'>" + name +"/" + brand + "</a></b> <br/>" + address;
          var icon =  customIcons[salescoverage] || 'https://maps.google.com/mapfiles/ms/icons/green-dot.png';
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
