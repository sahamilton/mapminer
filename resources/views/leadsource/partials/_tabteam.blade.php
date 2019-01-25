<h4>Team</h4>
<p><a  href="{{route('leadsource.announce',$leadsource->id)}}">
<button class="btn btn-info">Notify Sales Team</button></a></p>

<p>Prospects have been offered to the following sales reps;</p>

<table id ='sorttable1' class='table table-striped table-bordered table-hover'>
    <thead>


        <th>Sales Rep</th>
        <td>Offered Prospects</td>
        <td>Owned Prospects</td>
        <td>Closed Prospects</td>
        <td>Total Prospects</td>

    </thead>
    <tbody>
        @php $id=null;@endphp
        @foreach($teamStats as $team)

       {{dd($team)}}
            @if($team->id!=$id) 
            <tr> 
                <td>
                    <a href="{{route('leads.personsource',[$team->id,$leadsource->id])}}">
                        {{$team->name}}
                    </a>
                </td>
            @endif
            <td></td>
            <td>{{$team-></td>
            <td></td>
            <td></td>
        @if( $team->id != $id)
            @php $id = $team->id;@endphp
            </tr>
        @endif
        @endforeach

    </tbody>
</table>
