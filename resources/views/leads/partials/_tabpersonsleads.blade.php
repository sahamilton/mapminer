<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>Company</th>
    <th>Company Name</th>
    <th>City</th>
    <th>State</th>
    <th>Lead Source</th>
    <th>Date Created</th>
    <th>Rating</th>
    <th>Status</th>
 
       
    </thead>
    <tbody>

 @foreach($leads->salesleads as $lead)

    <tr>  
    <td>{{$lead->companyname}}</a></td>
    <td><a href="{{route('leads.show',$lead->id)}}">{{$lead->businessname}}</a></td>
    <td>{{$lead->city}}</td>
    <td>{{$lead->state}}</td>
    <td><a href="{{route('leads.personsource',[$leads->id,$lead->leadsource->id])}}">{{$lead->leadsource->source}}</a> </td>
    <td>{{$lead->created_at->format('m/d/Y')}}</td>

    <td>{{$lead->pivot->rating}}</td>
    <td>
        {{$statuses[$lead->pivot->status_id]}}
    </td>
	    
    
    </tr>
   @endforeach
    
    </tbody>
    </table>