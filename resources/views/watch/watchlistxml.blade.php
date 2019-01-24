<markers>
@foreach ($locations as $location)

<marker 
locationweb="{{route('locations.show',$location->location_id)}}"
name="{{trim($location->watching->businessname)}}"
account="{{trim($location->watching->businessname)}}"
accountweb="{{route('company.show' , $location->watching->company->id,['title'=>'see all locations'])}}"
address="{{$location->watching->street}} {{$location->watching->city}} {{$location->watching->state}}"
lat="{{$location->watching->lat}}"
lng="{{$location->watching->lng}}"
id="{{$location->location_id}}"
/>
@endforeach
</markers>