<p>

@foreach ($servicelines as $serviceline)

	{{$serviceline->ServiceLine}} =  <img src='https://maps.gstatic.com/mapfiles/ridefinder-images/mm_20_{{$serviceline->color}}.png' />

@endforeach




</p>