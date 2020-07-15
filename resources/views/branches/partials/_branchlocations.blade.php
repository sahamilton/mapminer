@foreach($addresses as $location)
    <tr>  
        <td>
            @if($location->company_id)
            <a href="{{route('company.show',$location->company_id)}}"
                    title="See all {{$location->companyname}} locations">
                    {{$location->companyname}}
            </a>
            @endif
        </td>
        <td>
            <a href="{{route('address.show',$location->id)}}"
                    title="See details of the {{$location->businessname}} location">
                    {{$location->businessname}}
            </a>
        </td>
        <td>{{$location->vertical}}</td>
        <td>{{$location->street}}</td>
        <td>{{$location->city}}</td>
        <td>{{$location->state}}</td>
        <td>{{$location->zip}}</td>

    </tr>
@endforeach