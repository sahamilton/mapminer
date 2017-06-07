<h4>Team</h4>


<p>Leads have been offered to the following sales reps;</p>

<table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>


        <th>Sales Rep</th>
        <td>Manager</td>
        <td>Location</td>
        <td>Leads</td>


    </thead>
    <tbody>

        @foreach($salesteams as $team)

        <tr>  
            <td><a href="{{route('leads.personsource',[$team->id,$leadsource->id])}}">{{$team->postName()}}</a></td>
            <td>
                @if(count($team->reportsTo)>0)
                    {{$team->reportsTo->postName()}}
                @endif
            </td>
            <td>{{$team->city}} {{$team->state}}</td>
            <td>{{count($team->salesleads)}}</td>
        </tr>
        @endforeach

    </tbody>
</table>
