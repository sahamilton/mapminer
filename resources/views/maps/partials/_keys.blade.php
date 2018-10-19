<p>

@foreach ($servicelines as $serviceline)

	{{$serviceline->ServiceLine}} =  <img src='{{asset('geocoding/markers/'.$serviceline->color.'-pin.png')}}' />

@endforeach




</p>