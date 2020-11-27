<table class="datatable">
    <thead>
        <th>Business</th>
        <th>Activity</th>
        <th>Activity Date</th>
        <th>City</th>
        <th>State</th>
        <th>Created / Updated</th>
        <th>Status</th>
    </thead>
    <tbody>
        @foreach($data['Activity'] as $activity)
        <tr>
            <td>
                <a href="{{route('address.show', $activity->address_id)}}"> 
                    {{$activity->relatesToAddress->businessname}}
                </a>
            </td>
            <td>{{$activity->type->activity}}</td>
            <td>{{$activity->activity_date->format('Y-m-d')}}</td>
            <td>{{$activity->relatesToAddress->city}}</td>
            <td>{{$activity->relatesToAddress->state}}</td>
            <td>
                {{max($activity->created_at, $activity->updated_at)->format('Y-m-d')}}
            </td>
            <td>{{$activity->completed == 1 ? 'Completed' : 'To Do'}}</td>
            
        </tr>
        @endforeach
    </tbody>
</table>