<h4>Team</h4>


<p>Leads have been offered to the following sales reps;</p>

<table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>


        <th>Sales Rep</th>
        <td>Manager</td>
        <td>Location</td>
        <td>Industry Focus</td>
        <td>Leads</td>


    </thead>
    <tbody>

        @foreach($salesteams as $team)

        <tr>  
            <td><a href="{{route('leads.personsource',[$team['details']->id,$leadsource->id])}}">{{$team['details']->postName()}}</a></td>
            <td>
                @if(count($team['details']->reportsTo)>0)
                    {{$team['details']->reportsTo->postName()}}
                @endif
            </td>
            <td>{{$team['details']->city}} {{$team['details']->state}}</td>
            <td>
                <ul>
                @foreach ($team['details']->industryfocus as $vertical)
                   
                    <li>{{$vertical->filter}}</li>
       
                @endforeach
                </ul>
            </td>
            <td>
                <ul>
                @foreach ($team['status'] as $key=>$value)
                    @if($value > 0)
                    <li>{{$statuses[$key]}} - {{$value}}</li>
                    @endif
                @endforeach
                </ul>
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
