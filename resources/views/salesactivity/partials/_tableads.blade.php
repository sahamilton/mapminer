 
<div class="row">
   @if(isset($owned))
    <div class="alert alert-danger">
        <p><strong>You must close or convert some of your {{$leads->ownedLeads->count()}} owned leads before accessing any of the 
        {{$leads->offeredLeads->count()}} additional leads available.</strong></p>
    </div>

   @else
	<h2>Leads Nearby in these verticals</h2>
<table id ='sorttable1' class='table table-striped table-bordered table-condensed table-hover'>
                <thead>

                <th>Company Name</th>
                
                <th>Business Name</th>
                <th>City</th>
                <th>State</th>
                <th>Status</th>
                <th>Industry Vertical</th>
                
                    <th>Actions</th>
                
                
            </thead>
            <tbody>

            @foreach ($leads as $lead)
            	<?php $status = $lead->myLeadStatus()->status_id;?>
                <tr> 
                
                <td>{{$lead->companyname }}</td>
                <td>
				@if((null !==$status) && $status == 2)
					<a href="{{route('salesleads.show',$lead->id)}}" />
					{{$lead->businessname}}</td>
				@else
                {{$lead->businessname}}
                @endif
                </td>
                <td>{{$lead->city}}</td>
                <td>{{$lead->state}}</td>
                <td>
                {{$statuses[$status]}}

              </td>
                <td>
                <ul>
                @foreach ($lead->vertical as $vertical)
                    <li>{{$vertical->filter}}</li>
                @endforeach
                </ul>
                </td>
                
<td>


		@if($status == 1) 
			@include('partials/_leadsmodal')
		
            <div class="btn-group">
			   <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				<a class="dropdown-item"
                    data-href="{{route('saleslead.accept',$lead->id)}}" data-toggle="modal" data-target="#accept-lead" data-title = "Some title" href="#">
                    <i class="fa fa-thumbs-up text-success" aria-hidden="true"></i> Claim Lead 
                </a>
                <a class="dropdown-item"
                     href="{{route('saleslead.decline',$lead->id)}}">
                    <i class="fa fa-thumbs-down text-danger" aria-hidden="true"></i> Decline Lead 
                </a>
               
			  </ul>
			</div>
	@endif
    </td>

    </tr>
   @endforeach
    
    </tbody>
    </table>
    @endif
</div>

