<h4>Team</h4>
<p><a  href="{{route('leadsource.announce',$leadsource->id)}}">
<button class="btn btn-info">Notify Sales Team</button></a></p>

<p>Prospects have been offered to the following sales reps;</p>

<table id ='sorttable1' class='table table-striped table-bordered table-hover'>
    <thead>


        <th>Sales Rep</th>
        <td>Manager</td>
        <td>Location</td>
       
        <td>Total Prospects</td>
        <td>Offered Prospects</td>
        <td>Owned Prospects</td>
        <td>Closed Prospects</td>


    </thead>
    <tbody>

        @foreach($salesteams as $team)

        <tr>  
            <td><a href="{{route('leads.personsource',[$team->id,$leadsource->id])}}">{{$team->postName()}}</a></td>
            <td>
                @if($team->reportsTo)
                    {{$team->reportsTo->postName()}}
                @endif
            </td>
            <td>{{$team->city}} {{$team->state}}</td>
            <td>{{$team->leads_count}}</td>
            <td>{{$team->offeredleads_count}}</td>
            <td>{{$team->ownedleads_count}}</td>         
            <td>{{$team->closedleads_count}}</td>
        </tr>
        @endforeach

    </tbody>
</table>
