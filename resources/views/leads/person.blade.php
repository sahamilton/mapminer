@extends ('admin.layouts.default')
@section('content')



<h3>Leads assigned to {{$leads->postName()}}</h3>




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

 @foreach($leads->salesleads as $lead)

    <tr>  
    <td>{{$lead->companyname}}</a></td>
    <td><a href="{{route('leads.show',$lead->id)}}">{{$lead->businessname}}</a></td>
    <td>{{$lead->city}}</td>
    <td>{{$lead->state}}</td>
    <td>{{$lead->created_at->format('M j, Y')}}</td>

    <td>{{$lead->rankMyLead($lead->salesteam, $leads->id)}}</td>
    <td>
    <?php $history = $lead->history($leads->id);?>

    @if(isset($history[$lead->id]['status']))
        <ul>

        @foreach ($history[$lead->id]['status'] as $state)

            @if($state['owner'] == $leads->id)
            <li>{{ $statuses[$state['status']]}}  {{$state['activitydate']->format('M j,Y')}}</li>
            @endif
        @endforeach
        </li>
    @endif
    </td>
	    
    
    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials._scripts')
@endsection