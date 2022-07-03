

<script src="https://maps.googleapis.com/maps/api/js?key={{config('maps.api_key')}}" ></script>

<script type="text/javascript">

  const address = { lat: {{$address->lat}}, lng: {{$address->lng}} };
  // The map, centered at Uluru
  const map = new google.maps.Map(document.getElementById("map"), {
    zoom: 14,
    center: address,
  });
  const marker = new google.maps.Marker({
    position: address,
    map: map,
    title:"{{$address->businessname}}",
  });


</script>