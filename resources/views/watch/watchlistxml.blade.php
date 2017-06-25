<markers>

@foreach ($locations as $location)
<marker 
locationweb="{{route('location.show',$location->id)}}"
name="{{trim($location->watching[0]->businessname)}}"
account="{{trim($location->watching[0]->businessname)}}",
accountweb="{{route('company.show' , $location->watching[0]->company->id,['title'=>'see all locations'])}}"
address="{{$location->watching[0]->street}} {{$location->watching[0]->city}} {{$location->watching[0]->state}}"
lat="{{$location->watching[0]->lat}}"
lng="{{$location->watching[0]->lng}}"
id="{{$location->location_id}}"
/>
@endforeach
</markers>