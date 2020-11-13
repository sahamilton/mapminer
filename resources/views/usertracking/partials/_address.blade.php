<table class="datatable">
    <thead>
        <th>Business</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Created / Updated</th>
    </thead>
    <tbody>
        @foreach($data['Address'] as $address)
        <tr>
            <td>{{$address->businessname}}</td>
            <td>{{$address->street}}</td>
            <td>{{$address->city}}</td>
            <td>{{$address->state}}</td>
            <td>
                {{max($address->created_at, $address->updated_at)->format('Y-m-d')}}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>