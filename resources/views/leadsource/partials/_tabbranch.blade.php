<h4>Team</h4>
<p><a  href="{{route('leadsource.announce',$leadsource->id)}}">
<button class="btn btn-info">Notify Branches</button></a></p>

<p>Leads have been offered to the following branches;</p>

<table id ='sorttable1' class='table table-striped table-bordered table-hover'>
    <thead>


        <th>Branch</th>
       
        <td>Offered Leads</td>
        <td>Claimed Leads</td>
        <td>Closed Leads</td>
      
        <td>Total Leads</td>

    </thead>
           
        @foreach($branches as $branch)
     
           <tr> 
                <td>
                    <a href="">
                        {{$branch->branchname}}
                    </a>
                </td>
                <td>{{$branch->assigned}}</td>
                <td>{{$branch->claimed}}</td>
                <td>{{$branch->closed}}</td>
                <td>{{$branch->leads_count}}</td>
          
        </tr>
        @endforeach

    </tbody>
</table>
