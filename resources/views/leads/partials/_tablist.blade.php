 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Company</th>
    <th>Business Name</th>
    <th>City</th>
    <th>State</th>
    <th>Date Created</th>
    <th>Vertical</th>
    <th>Rating</th>


    </thead>
    <tbody>

 @foreach($leads as $lead)

    <tr>
    <td><a href="{{route('leads.show',$lead->id)}}">{{ $lead->companyname!='' ? $lead->companyname: $lead->businessname}} </a></td>
    <td>{{$lead->businessname}}</td>
    <td>{{$lead->city}}</td>
    <td>{{$lead->state}}</td>
    <td>{{$lead->created_at->format('M j, Y')}}</td>
    
 <td>
    <ul>
        @if($lead->vertical)
    @foreach ($lead->vertical as $vertical)
        <li>{{$vertical->filter}}</li>

    @endforeach
@endif    </ul>
    <td> @if($lead->salesteam)
        @php $rank =   $lead->rankLead($lead->salesteam) @endphp

        {{$rank}}
@endif
    </td>



    </tr>
   @endforeach

    </tbody>
    </table>
