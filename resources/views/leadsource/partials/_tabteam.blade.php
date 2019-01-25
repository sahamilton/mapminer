<h4>Team</h4>
<p><a  href="{{route('leadsource.announce',$leadsource->id)}}">
<button class="btn btn-info">Notify Sales Team</button></a></p>

<p>Prospects have been offered to the following sales reps;</p>

<table id ='sorttable1' class='table table-striped table-bordered table-hover'>
    <thead>


        <th>Sales Rep</th>
        @foreach ($statuses as $status)
        <td>{{$status}} Prospects</td>
        @endforeach
        <td>Total Prospects</td>

    </thead>
    <tbody>
        @php $id=null;@endphp
        @foreach($teamStats as $team)

      
            @if($team->id!=$id) 
            <tr> 
                <td>
                    <a href="{{route('leads.personsource',[$team->id,$leadsource->id])}}">
                        {{$team->name}}
                    </a>
                </td>
            @endif
            @foreach($statuses as $key=>$status)
                @if($key == $team->status_id)
                    @php str_repeat("<td></td>",$team->status_id);@endphp
                    <td>{{$team->count}}</td>
                @endif
            @endforeach
        @if( $team->id != $id)
            @php $id = $team->id;@endphp
            </tr>
        @endif
        @endforeach

    </tbody>
</table>
