<p>


@foreach ($colors as $key=>$value)
	<?php strtolower($key) == 'not specified' ? $key = 'General' : $key;?>
	{{$key}} =  <img src='http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=%E2%80%A2|{{$value}}' />

@endforeach




</p>