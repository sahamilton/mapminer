<h4>Team</h4>
<p><a  href="{{route('leadsource.announce',$leadsource->id)}}">
<button class="btn btn-info">Notify Sales Team</button></a></p>

<p>Prospects have been offered to the following sales reps;</p>

<table id ='sorttable1' class='table table-striped table-bordered table-hover'>
    <thead>


        <th>Sales Rep</th>
        <td>Manager</td>
        <td>Location</td>
       
        <td>Prospects</td>


    </thead>
    <tbody>

        @foreach($salesteams as $team)

        <tr>  
            <td><a href="{{route('leads.personsource',[$team['details']->id,$leadsource->id])}}">{{$team['details']->postName()}}</a></td>
            <td>
                @if($team['details']->reportsTo->count()>0)
                    {{$team['details']->reportsTo->postName()}}
                @endif
            </td>
            <td>{{$team['details']->city}} {{$team['details']->state}}</td>
            <td>
               
                    {{$team->reportsTo ? $team->reportsTo->postName() : ''}}
              
            </td>
            <td>{{$team->city}} {{$team->state}}</td>
            
            <td>
                {{$team->leads->count()}}
            </td>
        </tr>
        @endforeach

    </tbody>
</table>
