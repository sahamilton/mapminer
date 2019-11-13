<table id="sorttable{{$loop->index}}"
    class="table table-striped"
    >
    <thead>
        <th>Business</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>ZIP</th>
    </thead>
    <tbody>
        @foreach ($branch->offeredLeads as $lead)
            @if($loop->iteration > 6)
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
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
