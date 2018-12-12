<h4>Team</h4>
<p><a  href="{{route('leadsource.announce',$leadsource->id)}}">
<button class="btn btn-info">Notify Sales Team</button></a></p>

<p>Prospects have been offered to the following sales reps;</p>

<table id ='sorttable1' class='table table-striped table-bordered table-hover'>
    <thead>


        <th>Sales Rep</th>
        <td>Manager</td>
        <td>Location</td>
       
        
        <td>Offered Prospects</td>
        <td>Owned Prospects</td>
        <td>Closed Prospects</td>
        <td>Total Prospects</td>

    </thead>
    <tbody>

        @foreach($salesteams as $team)

        <tr>  
            <td>
                <a href="{{route('leads.personsource',[$team->id,$leadsource->id])}}">
                    {{$team->postName()}}
                </a>
            </td>
            <td>
                @if($team->reportsTo)
                    {{$team->reportsTo->postName()}}
                @endif
            </td>
            <td>{{$team->city}} {{$team->state}}</td>
            <td>{{$teamStats[$team->id][1]}}</td>
            <td>{{$teamStats[$team->id][2]}}</td>
            <td>{{$teamStats[$team->id][3]}}</td>
            <td>{{$teamStats[$team->id][1] + $teamStats[$team->id][2] + $teamStats[$team->id][3]}}</td>
        </tr>
        @endforeach

    </tbody>
</table>
