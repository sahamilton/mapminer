<script type="text/javascript">
    //<![CDATA[

    var customIcons = {
      @foreach ($servicelines as $serviceline)
      '{{$serviceline->ServiceLine}}': {
        icon: '//maps.google.com/mapfiles/ms/icons/{{$serviceline->color}}-dot.png'
      },
      @endforeach
    };

    function load() {
      var map = new google.maps.Map(document.getElementById("map"), {
        center: new google.maps.LatLng(39.5,-98.4),
        zoom: 4,
        mapTypeId: 'roadmap'
      });
      var infoWindow = new google.maps.InfoWindow;

      // Change this depending on the name of your PHP file
      downloadUrl("/api/branch/map", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
        for (var i = 0; i < markers.length; i++) {
          var name = markers[i].getAttribute("name");
          var address = markers[i].getAttribute("address");
          var brand = markers[i].getAttribute("brand");
          var color = markers[i].getAttribute("color");

    		  var branchlink = markers[i].getAttribute("locationweb");
    		  var linktitle = "Review the " + name + " branch";
          var point = new google.maps.LatLng(
              parseFloat(markers[i].getAttribute("lat")),
              parseFloat(markers[i].getAttribute("lng"))); 
          var html = "<b><a href='" + branchlink + "' title ='" +linktitle + "'>" + name +"/" + brand + "</a></b> <br/>" + address;
          var icon =  customIcons[brand] || 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png';
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