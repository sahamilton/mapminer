@extends('site/layouts/default')
@section('content')

<h1>All Managers</h1>


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
                <td>{{$lead->businessname}}</td>
                <td>{{$lead->city}}</td>
                <td>{{$lead->state}}</td>
                <td>{{$lead->pivot->status_id}}
                <td>
                <ul>
                @foreach ($lead->vertical as $vertical)
                    <li>{{$vertical->filter}}</li>
                @endforeach
                </ul>
                </td>
<td>
            @include('partials/_leadsmodal')
    
            <div class="btn-group">
			   <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
				<span class="sr-only">Toggle Dropdown</span>
			  </button>
			  <ul class="dropdown-menu" role="menu">
				

				<li><a data-href="{{route('saleslead.accept',$lead->id)}}" data-toggle="modal" data-target="#confirm-delete" data-title = "Some title" href="#">
                <i class="glyphicon glyphicon-view"></i> View </a></li>
			  </ul>
			</div>
	
    </td>

    </tr>
   @endforeach
    
    </tbody>
    </table>





@include('partials/_scripts')



@stop