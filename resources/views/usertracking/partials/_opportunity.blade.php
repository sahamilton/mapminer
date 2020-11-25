@php
$statuses = [0=>'Open', 1=>'Closed Won', 2=>'Closed Lost'];
@endphp
<table class="datatable">
    <thead>
        <th>Business</th>
        <th>Opportunity</th>
        <th>City</th>
        <th>State</th>
        <th>Created / Updated</th>
        <th>Value</th>
        <th>Current Status</th>

    </thead>
    <tbody>
        @foreach($data['Opportunity'] as $opportunity)
        <tr>
            <td>
                <a href="{{route('address.show', $opportunity->address_id)}}"> 
                    {{$opportunity->location->businessname}}
                </a>
            </td>
            <td>
                <a href="{{route('opportunity.show', $opportunity->id)}}" >
                    {{$opportunity->title}}
                </a>
            </td>
            <td>{{$opportunity->location->city}}</td>
            <td>{{$opportunity->location->state}}</td>
            <td>
                {{max($opportunity->created_at, $opportunity->updated_at)->format('Y-m-d')}}
            </td>
            <td>${{number_format($opportunity->value,0)}}</td>
            <td>{{$statuses[$opportunity->closed]}}</td>
        </tr>
        @endforeach
    </tbody>
</table>