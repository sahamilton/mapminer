
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
        <td>@if ($lead->open_opportunities_count >0)
               {{$lead->open_opportunities_count}}
            @endif 
        </td>
        <td>{{$lead->lastActivity ? $lead->lastActivity->activity_date->format('Y-m-d') : ''}}</td>
    </tr>
@endforeach