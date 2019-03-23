<markers>
@foreach ($locations as $location)

@if($location->watching)
<marker 
locationweb="{{route('address.show',$location->address_id)}}"
name="{{trim($location->watching->businessname)}}"
account="{{trim($location->watching->businessname)}}"
accountweb="{{route('address.show' , $location->id)}}"
address="{{$location->watching->street}} {{$location->watching->city}} {{$location->watching->state}}"
lat="{{$location->watching->lat}}"
lng="{{$location->watching->lng}}"
id="{{$location->location_id}}"
/>
@endif
@endforeach
</markers>