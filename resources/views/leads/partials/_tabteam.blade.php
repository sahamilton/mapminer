<h4>Team</h4>


<p>Prospects have been offered to the following Mapminer users;</p>

<table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>


        <th>Sales Rep</th>
        <td>Manager</td>
        <td>Industry Focus</td>
        <td>Location</td>
        <td>Prospects</td>


    </thead>
    <tbody>

        @foreach($salesteams as $team)

        <tr>  
            <td><a href="{{route('leads.person',$team->id)}}">{{$team->postName()}}</a></td>
            <td>
                @if(count($team->reportsTo)>0)
                    {{$team->reportsTo->postName()}}
                @endif
            </td>
            <td>
            <ul>
            @foreach($team->industryfocus as $vertical)
                <li>{{$vertical->filter}}</li>
            @endforeach
            <td>{{$team->city}} {{$team->state}}</td>
            <td>{{count($team->salesleads)}}</td>
        </tr>
        @endforeach

    </tbody>
</table>
