<p>
@php $addressKeys = [

'customer'=>'red',
'project'=>'darkgreen',
'location'=>'blue',
'lead'=>'yellow',
]
@endphp
@foreach ($addressKeys as $key=>$color)

	{{$key}} =  <img src='{{asset('geocoding/markers/'.$color.'-pin.png')}}' />

@endforeach



</p>