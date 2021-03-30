@foreach($locations as $location)
    <tr>
        <td><a href="{{route('address.show', $location->id)}}">{{$location->businessname}}</a></td>
        <td>{{$location->street}}</td>
        <td>{{$location->city}}</td>
        <td>{{$location->state}}</td>
        <td>
            @foreach ($location->assignedToBranch as $branch)
                <li><a href="{{route('branches.show', $branch->id)}}" title="Visit {{$branch->branchname}}">{{$branch->id}}</a></li>
            @endforeach
        </td>
        <td>{{number_format($location->distance,2)}}</td>
    </tr>
@endforeach