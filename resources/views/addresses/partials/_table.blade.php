
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
        <td>{{$lead->openOpportunities->count()}}</td>
        <td>{{$lead->lastActivity->count() ? $lead->lastActivity->first()->activity_date->format('Y-m-d') : ''}}</td>
    </tr>
@endforeach