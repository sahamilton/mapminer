<h4>Team</h4>
<p><a  href="{{route('leadsource.announce',$leadsource->id)}}">
<button class="btn btn-info">Notify Branches</button></a></p>

<p>Prospects have been offered to the following branches;</p>

<table id ='sorttable1' class='table table-striped table-bordered table-hover'>
    <thead>


        <th>Branch</th>
        @foreach ($statuses as $status)
        <td>{{$status}} Prospects</td>
        @endforeach
        <td>Total Prospects</td>

    </thead>
           
        @foreach($branchStats as $branch=>$stats)
      
           <tr>  @php $total = 0;@endphp
                <td>
                    <a href="{{route('leads.personsource',[$branch,$leadsource->id])}}">
                        {{$stats['branchname']}}</a>
                </td>

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
