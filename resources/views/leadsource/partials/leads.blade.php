<h4>Leads</h4>




    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>Company</th>
    <th>Business Name</th>
    <th>City</th>
    <th>State</th>
    <th>Date Created</th>

    <th>Rating</th>
    <th>Status</th>
 
       
    </thead>
    <tbody>

 @foreach($leadsource->leads as $lead)
   	
    <tr>  
    <td><a href="{{route('leads.show',$lead->id)}}">{{$lead->companyname}}</a></td>
    <td>{{$lead->businessname}}</td>
    <td>{{$lead->city}}</td>
    <td>{{$lead->state}}</td>
    <td>{{$lead->created_at->format('M j, Y')}}</td>
    <td>{{$lead->rankLead($lead->salesteam)}}</td>
    <td>
    <?php $history = $lead->history();?>
    @if(isset($history[$lead->id]['status']))
        <ul>

        @foreach ($history[$lead->id]['status'] as $state)

            <li>{{ $statuses[$state['status']]}}  {{$state['activitydate']->format('M j,Y')}}</li>
        @endforeach
        </li>
    @endif
    </td>
	    
    
    </tr>
   @endforeach
    
    </tbody>
    </table>
