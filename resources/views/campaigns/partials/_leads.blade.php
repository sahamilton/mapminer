<table class="table table-striped">
     
    <thead>
        <th>Business</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>ZIP</th>
        <th>Last activity</th>
    </thead>
    <tbody>
       
        @foreach ($data as $lead)
        <tr>
            <td>
                <a href="{{route('address.show', $lead->id)}}">
                    {{$lead->businessname}}
                </a>
            </td>
            <td>{{$lead->street}}</td>
            <td>{{$lead->city}}</td>
            <td>{{$lead->state}}</td>
            <td>{{$lead->zip}}</td>
            <td>{{$lead->lastactivity ? $lead->lastactivity->activity_date->format('Y-m-d') : ''}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
