

  
<div class="row">
   @if(count($leads->ownedLeads) >=$limit)
    <div class="alert alert-danger">
        <p><strong>You must close some of your {{count($leads->ownedLeads)}} owned prospects before accessing any of the 
        {{count($leads->offeredLeads)}} additional prospects available.</strong></p>
    </div>

   @else

<table id ='sorttable2' class='table table-striped table-bordered table-condensed table-hover'>
                <thead>

                <th>Company Name</th>
                <th>Business Name</th>
                <th>City</th>
                <th>State</th>
               
                <th>Industry Vertical</th>
                <th>Actions</th>
               
                
            </thead>
            <tbody>

            @foreach ($leads->offeredLeads as $lead)
      
                <tr> 
                
                <td>{{$lead->companyname}}</td>
                <td>{{$lead->businessname}}</td>
                <td>{{$lead->address->city}}</td>
                <td>{{$lead->address->state}}</td>
                
                <td>
                    <ul>
                    @foreach ($lead->vertical as $vertical)
                        <li>{{$vertical->filter}}</li>
                    @endforeach
                    </ul>
                </td>
               
<td>
        @if(in_array($lead->pivot->status_id,[1]) )

    
         @include('partials/_leadsmodal')     
         <div class="btn-group">
			   <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
			  @if($lead->pivot->status_id ==1)
				<li><a data-href="{{route('saleslead.accept',$lead->id)}}" data-toggle="modal" data-target="#accept-lead" data-title = "Some title" href="#">
                <i class="fa fa-thumbs-up" aria-hidden="true"></i> Claim Prospect </a></li>
                <li><a href="{{route('saleslead.decline',$lead->id)}}">
                <i class="fa fa-thumbs-down" aria-hidden="true"></i> Decline Prospect </a></li>


               @endif

               @if($lead->pivot->status_id ==2)
				<li><a href="">
				<i class="fa fa-hand-o-right" aria-hidden="true"></i>
				Work  Prospect </a></li>
               @endif
			  </ul>
			</div>

    </td>
@endif
    </tr>
   @endforeach
  
    </tbody>
    </table>
    @endif
</div>

