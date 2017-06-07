 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>Company</th>
    <th>Business Name</th>
    <th>City</th>
    <th>State</th>
    <th>Date Created</th>
    <th>Status</th>
    <th>Source</th>
    <th>Rating</th>
 @if (Auth::user()->hasRole('Admin'))
 <th>Actions</th>
 @endif  
       
    </thead>
    <tbody>

 @foreach($leads as $lead)

    <tr>  
    <td><a href="{{route('leads.show',$lead->id)}}">{{$lead->companyname}}</a></td>
    <td>{{$lead->businessname}}</td>
    <td>{{$lead->city}}</td>
    <td>{{$lead->state}}</td>
    <td>{{$lead->created_at->format('M j, Y')}}</td>
    <td> 

    @if(count($lead->salesteam) > 0)

        

        @if(null !== $lead->leadOwner($lead->id))

           {{$statuses[$lead->leadOwner($lead->id)->pivot->status_id]}}  by {{$lead->leadOwner($lead->id)->postName()}}

        @else
            Offered {{count($lead->salesteam)}}
        @endif
    
    @endif</td>
    <td><a href = "{{route('leadsource.show',$lead->lead_source_id)}}">{{$sources[$lead->lead_source_id]}}</a></td>
    <td>    {{$lead->rankLead($lead->salesteam)}}

    </td>
	@if (Auth::user()->hasRole('Admin'))
    <td>
            @include('partials/_modal')
    
            <div class="btn-group">
			  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
				<li><a href="{{route('leads.edit',$lead->id)}}"><i class="glyphicon glyphicon-pencil"></i> Edit this lead</a></li>
				<li><a data-href="{{route('leads.purge',$lead->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = " this lead and all its history" href="#"><i class="glyphicon glyphicon-trash"></i> Delete this lead</a></li>
			  </ul>
			</div>
		
		
    </td>

   @endif 
    
    
    </tr>
   @endforeach
    
    </tbody>
    </table>