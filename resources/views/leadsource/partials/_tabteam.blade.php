<h4>Team</h4>
<p><a  href="{{route('leadsource.announce',$leadsource->id)}}">
<button class="btn btn-info">Notify Sales Team</button></a></p>

<p>Leads have been offered to the following sales reps;</p>

<table id ='sorttable1' class='table table-striped table-bordered table-hover'>
    <thead>


        <th>Sales Rep</th>
        @foreach ($statuses as $status)
        <td>{{$status}} Leads</td>
        @endforeach
        <td>Total Leads</td>

    </thead>
           
        @foreach($teamStats as $person=>$stats)
          
           <tr>  @php $total = 0;@endphp
                <td>{{$stats['name']}}</td>

            @foreach($statuses as $key=>$status)
            
                <td>
                @if(isset($stats[$key]))
                   
                   {{$stats[$key]}} 
                   @php $total += $stats[$key];@endphp
                @endif
                </td>
            @endforeach
            <td>{{$total}}</td>
        </tr>
        @endforeach

    </tbody>
</table>
