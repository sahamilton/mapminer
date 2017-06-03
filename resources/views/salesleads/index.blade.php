@extends('site/layouts/default')
@section('content')

<h1>Leads Assigned to {{$leads->firstname}} {{$leads->lastname}}</h1>


<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
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

            @foreach ($leads->salesleads as $lead)
        
                <tr> 
                
                <td>{{$lead->companyname }}</td>
                <td>
				@if(isset($lead->pivot) && $lead->pivot->status_id == 2)
					<a href="{{route('salesleads.show',$lead->id)}}" />
					{{$lead->businessname}}</td>
				@else
                {{$lead->businessname}}
                @endif
                </td>
                <td>{{$lead->city}}</td>
                <td>{{$lead->state}}</td>
                <td>
                {{$statuses[$lead->pivot->status_id]}}

               {{$lead->pivot->status_id}}
                <td>
                <ul>
                @foreach ($lead->vertical as $vertical)
                    <li>{{$vertical->filter}}</li>
                @endforeach
                </ul>
                </td>
<td>
           @if(in_array($lead->pivot->status_id,['1',2]))
            @include('partials/_leadsmodal')
    
            <div class="btn-group">
			   <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				
			  @if($lead->pivot->status_id ==1)
				<li><a data-href="{{route('saleslead.accept',$lead->id)}}" data-toggle="modal" data-target="#accept-lead" data-title = "Some title" href="#">
                <i class="glyphicon glyphicon-thumbs-up"></i> Claim Lead </a></li>
                <li><a href="{{route('saleslead.decline',$lead->id)}}">
                <i class="glyphicon glyphicon-thumbs-down"></i> Decline Lead </a></li>


               @endif

               @if($lead->pivot->status_id ==2)
				<li><a href="">
				<i class="glyphicon glyphicon-hand-right"></i>
				Work  Lead </a></li>
               @endif
			  </ul>
			</div>
	@endif
    </td>

    </tr>
   @endforeach
    
    </tbody>
    </table>





@include('partials/_scripts')



@stop