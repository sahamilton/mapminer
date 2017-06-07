<h4>Team</h4>


<p>Leads have been offered to the following sales reps;</p>

<table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>


        <th>Sales Rep</th>
        <td>Manager</td>
        <td>Location</td>


    </thead>
    <tbody>

        @foreach($salesteams as $team)
        <tr>  
            <td>{{$team->postName()}}</td>
            <td>
                @if(count($team->reportsTo)>0)
                    {{$team->reportsTo->postName()}}
                @endif
            </td>
            <td>{{$team->city}} {{$team->state}}</td>
        </tr>
        @endforeach

    </tbody>
</table>
