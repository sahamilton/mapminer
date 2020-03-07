
@foreach($leads as $lead)
    <tr>
        <td>
            <a href="{{route('address.show',$lead->id)}}">
                {{ $lead->businessname }} 
            </a>
        </td>
        <td>{{$lead->street}}</td>
        <td>{{$lead->city}}</td>
        <td>{{$lead->state}}</td>
        <td>{{$lead->open_opportunities_count}}</td>
    </tr>
@endforeach