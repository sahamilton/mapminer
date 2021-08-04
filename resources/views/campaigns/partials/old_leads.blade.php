<table class="table table-striped">
     
    <thead>
        <th>Business</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>ZIP</th>
    </thead>
    <tbody>
       
        @foreach ($data as $lead)
        <tr>
            <td>
                <a href="{{route('address.show', $lead->id)}}">
                    {{$lead->businessname}}
                </a>
            </td>
            <td>{{$lead->address}}</td>
            <td>{{$lead->street}}</td>
            <td>{{$lead->state}}</td>
            <td>{{$lead->zip}}</td>
        </tr>
        @endforeach
    </tbody>
</table>
