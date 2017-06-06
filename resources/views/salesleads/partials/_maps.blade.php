    <script src="https://maps.google.com/maps/api/js?key={{config('maps.api_key')}}"></script>
    <script>
     
      // First, create an object containing LatLng and details for each branch.
      var leadmap = {
      <?php
      foreach($leads->salesleads as $lead){
              if(! in_array($lead->pivot->status_id,[1,2])){
                continue;
              }
             if ($lead->pivot->status_id  == 2){ 
                    if($manager){
                      $content="<a href=\"".route('salesleads.showrepdetail',[$lead->id,$leads->id])."\" title=\"See details of owned lead\">".$lead->businessname."</a>";;
                    }else{
                    $content="<a href=\"".route('salesleads.show',$lead->id)."\" title=\"See details of owned lead\">".$lead->businessname."</a>";
                    }   
                    $color ='green';
                    $type='owned';
             }elseif ($lead->pivot->status_id  == 1){
               
                    $content="<a href=\"".route('saleslead.accept',$lead->id)."\" title=\"Accept lead\">Accept ".$lead->businessname." lead</a>";
                    $color ='blue';
                    $type='offered';
                    if($manager){
                      $content = $lead->businessname;
                    }
             }

              echo "'".$lead->businessname."':{";
              echo "center: {lat: ". $lead->lat .", lng:". $lead->lng."},";
              echo "name : '" . $lead->businessname."',";
              echo "type:  '" . $type."',";
              echo "contentString: '". $content ."'},"; 
               
                
            }
            echo "};"?>
      function initMap() {
        // Create the map.
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 9,
          center: {lat: {{$leads->lat}}, lng: {{$leads->lng}}},
          mapTypeId: 'terrain'
        });

      var iconBase = '{{asset("assets/icons/")}}';
      var icons = {
        owned: {
          icon: iconBase + '/greenflagsm.png'
        },
        offered: {
          
          icon: iconBase + '/orangeflagsm.png'
        }
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