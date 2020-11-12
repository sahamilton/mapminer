@foreach($locations as $location)
    <tr>
        <td>{{$location->businessname}}</td>
        <td>{{$location->street}}</td>
        <td>{{$location->city}}</td>
        <td>{{$location->state}}</td>
    </tr>
@endforeach