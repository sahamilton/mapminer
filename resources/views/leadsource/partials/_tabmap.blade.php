<div id="map" style="border:solid 1px red"></div>

<script src="//maps.google.com/maps/api/js?key={{config('maps.api_key')}}"></script>
    <script>
     
      // First, create an object containing LatLng and details for each branch.
      var leadmap = {
      <?php
      foreach($leadsource->leads as $lead){
      		 $status = null;
             if($lead->salesteam->count()>0){
             
             	foreach ($lead->salesteam as $team){
                
             		if($team->pivot->status_id > $status && in_array($team->pivot->status_id,[1,2,3])){
             			$status = $team->pivot->status_id;
             		}
             	}
             }

              echo "'".str_replace("'", "",$lead->businessname)."':{";
              echo "center: {lat: ". $lead->lat .", lng:". $lead->lng."},";
              echo "name : '".str_replace("'", "",$lead->businessname)."',";
              echo "contentString: '<a href=\"".route('leads.show',$lead->id)."\">". str_replace("'", "",$lead->businessname) ." </a> ',"; 
              if(isset($status) && in_array($status,[1,2,3])){
              	
              	echo "type:  '".$statuses[$status]."'},";
              }else{
              	
              	echo "type: 'Default'},";
              }
              
               
                
            }
            echo "};"?>
      function initMap() {
        // Create the map.
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: {lat: 39.8281595, lng: -98.5795444},
          mapTypeId: 'terrain'
        });

      var iconBase = '{{asset("assets/icons/")}}';
      var icons = {
        'Closed':{
 			    icon: '//maps.google.com/mapfiles/ms/icons/green-dot.png'
        },

        'Owned': {
          icon: '//maps.google.com/mapfiles/ms/icons/yellow-dot.png'
        },
        'Offered': {
          
          icon: '//maps.google.com/mapfiles/ms/icons/red-dot.png'
        },

        'Default': {
        	icon: '//maps.google.com/mapfiles/ms/icons/red-dot.png'
        },
      };

      var infowindow = new google.maps.InfoWindow();
        // Construct the circle for each value in citymap.
        // Note: We scale the area of the circle based on the population.
        for (var lead in leadmap) {
          // Add the circle for this city to the map.
          
          var marker = new google.maps.Marker({
            map: map,
            icon: icons[leadmap[lead].type].icon,
       
            position: new google.maps.LatLng(leadmap[lead].center),
            title: leadmap[lead].name,
          });
          
          var content = leadmap[lead].contentString;
         
           google.maps.event.addListener(marker,'click', (function(marker,content,infowindow){ 
        return function() {

           infowindow.setContent(content);
           infowindow.open(map,marker);
        };
    })(marker,content,infowindow)); 



        }
       } 

      google.maps.event.addDomListener(window, 'load', initMap);
    </script>

