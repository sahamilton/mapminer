<h4>Unassigned Leads</h4>

    <table id ='sorttable2' class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
         
        <th>Company</th>
        <th>Business Name</th>
        <th>City</th>
        <th>State</th>
        <th>Date Created</th>
           
        </thead>
        <tbody>

            @foreach($leadsource->unassignedLeads as $lead )

               
                    <tr>  
                        <td><a href="{{route('leads.show',$lead->id)}}">{{$lead->companyname}}</a></td>
                        <td>{{$lead->businessname}}</td>
                        <td>{{$lead->city}}</td>
                        <td>{{$lead->state}}</td>
                        <td>{{$lead->created_at->format('M j, Y')}}</td>
                    </tr>

            @endforeach
        
        </tbody>
    </table>
    
    @if($leadsource->unassignedLeads )
    <p><a href="{{route('leadsource.assign',$leadsource->id)}}"><button class="btn btn-info"  > Assign Leads Geographically</button></a></p>
    @endif