@foreach($addresses as $location)
    <tr>  
        
        <td>
            @if($location->company)
             <a href="{{route('company.show',$location->company->id)}}"
                title="See all locations of {{$location->company->companyname}}">
                {{$location->company->companyname ." / "}}
            </a>
            @endif
            <a href="{{route('address.show',$location->id)}}"
                    title="See details of this {{$location->businessname}} location">
                    {{$location->businessname}}
            </a>
        </td>
        <td>{{$location->industryVertical ? $location->industryVertical->filter : ''}}</td>
        <td>{{$location->street}}</td>
        <td>{{$location->city}}</td>
        <td>{{$location->state}}</td>
        <td>{{$location->zip}}</td>
        <td>{{number_format($location->distance, 2)}}</td>

    </tr>
@endforeach