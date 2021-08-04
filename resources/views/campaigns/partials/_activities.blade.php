<table class="table table-striped">
    <thead>
        <th>Date</th>
        <th>Company</th>
        <th>Activity</th>
        <th>Contact</th>
        <th>Notes</th>
        <th>Status</th>
        <th>Actions</th>
    </thead>
    <tbody>
       
        @foreach ($data as $activity)
         <tr>
                <td>{{$activity->activity_date ? $activity->activity_date->format('Y-m-d'):''}}</td>
                <td>
                    <a href="{{route('address.show', $activity->address_id)}}">{{$activity->relatesToAddress->businessname}}
                    </a>
                </td>
                <td>@if($activity->type)
                    {{$activity->type->activity}}
                    @endif
                </td>
                <td>@foreach($activity->relatedContact as $contact)
                    <li>{{$contact->fullname}}</li>
                    @endforeach
                    
                </td>
                <td>{{$activity->note}}</td>
                <td>{{$activity->completed ? 'Completed' : ''}}</td>
                
            </tr>
           @endforeach
    </tbody>
</table>
