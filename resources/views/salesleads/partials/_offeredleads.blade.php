<div class="row">
   @if($leads->ownedLeads->count() >=$limit)
    <div class="alert alert-danger">
        <p><strong>You must close some of your {{$leads->ownedLeads->count()}} owned leads before accessing any of the 
        {{$leads->offeredLeads->count()}} additional Leads available.</strong></p>
    </div>

   @else

    <table id ='sorttable2' class='table table-striped table-bordered table-condensed table-hover'>
        <thead>

            <th>Company Name</th>
            <th>Company Name</th>
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
            <td>{{$lead->city}}</td>
            <td>{{$lead->state}}</td>
            
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
    				<a class="dropdown-item"
                        data-href="{{route('saleslead.accept',$lead->id)}}" 
                        data-toggle="modal" 
                        data-target="#accept-lead" 
                        data-title = "claim Lead" 
                        href="#">
                        <i class="far fa-thumbs-up" aria-hidden="true"></i> 
                        Claim Lead 
                    </a>
                    <a class="dropdown-item" 
                        href="{{route('saleslead.decline',$lead->id)}}">
                        <i class="far fa-thumbs-down" aria-hidden="true"></i> 
                        Decline Lead 
                    </a>



                   @endif

                   @if($lead->pivot->status_id ==2)
    				<a class="dropdown-item" href="">
    				<i class="far fa-hand-o-right" aria-hidden="true"></i>
    				Work  Lead </a>
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

