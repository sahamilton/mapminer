 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>National Account</th>
    <th>Company Name</th>
    <th>City</th>
    <th>State</th>
    <th>Date Created</th>
    <th>Vertical</th>
    <th>Rating</th>


    </thead>
    <tbody>

 @foreach($leads as $lead)

    <tr>
    <td>
        @if ($lead->companyname!='') 
            <a href="{{route('leads.show',$lead->id)}}">{{ $lead->companyname}} </a>
        @endif
    </td>
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
