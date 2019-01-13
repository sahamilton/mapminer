<div class="row">
   

@if($data['type'] == 'branch'))
@foreach ($servicelines as $serviceline)

	{{$serviceline->ServiceLine}} =  <img src='{{asset('geocoding/markers/'.$serviceline->color.'-pin.png')}}' />

@endforeach
@elseif($data['type'] == 'location')

	@php 
		$addressKeys = [

		'customer'=>'red',
		'project'=>'darkgreen',
		'location'=>'blue',
		'lead'=>'yellow',
		];
	@endphp
	@foreach ($addressKeys as $key=>$color)

		{{ucwords($key)}}&nbsp = &nbsp <img src='{{asset('geocoding/markers/'.$color.'-pin.png')}}' />&nbsp&nbsp&nbsp

	@endforeach





@endif
</div>