 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Company</th>
    <th>Business Name</th>
    <th>City</th>
    <th>State</th>
    <th>Date Created</th>
    <th>Status</th>
    <th>Source</th>
    <th>Vertical</th>
    <th>Rating</th>


    </thead>
    <tbody>

 @foreach($leads as $lead)

    <tr>
    <td><a href="{{route('leads.show',$lead->id)}}">{{ $lead->companyname!='' ? $lead->companyname: $lead->businessname}} </a></td>
    <td>{{$lead->businessname}}</td>
    <td>{{$lead->address->city}}</td>
    <td>{{$lead->address->state}}</td>
    <td>{{$lead->created_at->format('M j, Y')}}</td>
    <td>
    @if($lead->salesteam)
        @if($lead->ownedBy)

           {{$statuses[$lead->ownedBy[0]->pivot->status_id]}}  by {{$lead->ownedBy[0]->fullName()}}

        @else
            Offered {{$lead->salesteam->count()}}
        @endif
    @else
    Unassigned
    @endif</td>
    <td><a href = "{{route('leadsource.show',$lead->lead_source_id)}}">{{$sources[$lead->lead_source_id]}}</a></td>
    <td>
    <ul>
    @foreach ($lead->vertical as $vertical)
        <li>{{$vertical->filter}}</li>

    @endforeach
    </ul>
    <td>  @php $rank =   $lead->rankLead($lead->salesteam) @endphp

        {{$rank}}

    </td>



    </tr>
   @endforeach

    </tbody>
    </table>
