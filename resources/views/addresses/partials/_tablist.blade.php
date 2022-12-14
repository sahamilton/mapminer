 <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>National Account</th>
    <th>Company Name</th>
    <th>City</th>
    <th>State</th>
    <th>Date Created</th>
    <th>Source</th>
    <th>Status</th>
    <th>Claim / Decline</th>

    </thead>
    <tbody>
@if (isset($data['result']))
@php $leads = $data['result'] @endphp
@endif
 @foreach($leads as $lead)

    <tr>
        <td>
            @if($lead->companyname != '')
            <a href="{{route('address.show',$lead->id)}}">
                {{ $lead->companyname }} 
            </a>
            @endif
        </td>
        <td>{{$lead->businessname}}</td>
        <td>{{$lead->city}}</td>
        <td>{{$lead->state}}</td>
        <td>{{$lead->created_at->format('M j, Y')}}</td>
       
        <td>{{$lead->leadsource->source}}</td>
        
        <td>
            @if($lead->salesteam->count() > 0)
                {{$statuses[$lead->salesteam->first()->pivot->status_id]}}
            @else
                Unclaimed
            @endif
        </td>
        <td> @if($lead->salesteam->count() > 0)
            @if($lead->salesteam->first()->pivot->status_id==1)
            <div class="btn-group">
               <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
              </button>
              <ul class="dropdown-menu" role="menu">

                
                <a class="dropdown-item"
                     href="{{route('saleslead.decline',$lead->id)}}">
                    <i class="far fa-thumbs-down text-danger" aria-hidden="true"></i> Decline Lead 
                </a>

               
              </ul>
            </div>
            @endif
        @endif
    </td>



    </tr>
   @endforeach

    </tbody>
    </table>
   
