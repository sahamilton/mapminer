<table class="table" id = "sorttable">
    <thead>
        <th>Company</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Notes</th>
        <th>Ranking</th>
    </thead>
    <tbody>
        @foreach ($closedleads as $lead)
        <tr> 
            <td><a href="{{route('salesrep.newleads.show',$lead->id)}}">{{$lead->companyname}}</a></td>
            <td>{{$lead->address}}</td>
            <td>{{$lead->city}}</td>
            <td>{{$lead->state}}</td>
            <td>
                @foreach ($lead->relatedNotes as $note)
                    {{$note->note}}<hr />
                @endforeach
            </td>
            <td>
               {{$lead->closedleads->first()->pivot->rating}}
               <span data-rating="{{$lead->salesteam->first()->pivot->rating}}" class="starrr" style="color:#E77C22"></span>
            </td>
        </tr>  

        @endforeach
    </tbody>



</table>