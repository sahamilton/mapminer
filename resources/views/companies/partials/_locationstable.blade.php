@foreach($locations as $location)
    <tr>
        <td>{{$location->businessname}}</td>
        <td>{{$location->street}}</td>
        <td>{{$location->city}}</td>
        <td>{{$location->state}}</td>
        <td>
            @foreach ($location->assignedToBranch as $branch)
                <li><a href="{{route('branches.show', $branch->id)}}" title="Visit {{$branch->branchname}}">{{$branch->id}}</a>
            @endforeach
    </tr>
@endforeach