<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    <th>National Account</th>
    <th>Company Name</th>
    <th>City</th>
    <th>State</th>
    <th>Date Created</th>
    <th>Leadsource</th>
    <th>Vertical</th>
    <th>Rating</th>
    <th>Remove</th>
    </thead>
    <tbody>
        @foreach($leads as $lead)
            <tr>
                <td>
                    <a href="{{route('address.show',$lead->id)}}">
                        {{ $lead->companyname!='' ? $lead->companyname:''}} 
                    </a>
                </td>
                <td>{{$lead->businessname}}</td>
                <td>{{$lead->city}}</td>
                <td>{{$lead->state}}</td>
                <td>@if($lead->created_at)
                        {{$lead->created_at->format('M j, Y')}}
                   
                    @endif
                </td>
                <td>{{$lead->leadsource->source}}</td>
                <td>{{$lead->pivot->Top25}}</td>
                <td>{{$lead->pivot->rating}}</td>
                <td>
                    <a 
                        data-href="{{route('branch.lead.remove',$lead->id)}}" 
                        data-toggle="modal" 
                        data-target="#confirm-remove" 
                        data-title = " this lead from your list" 
                        href="#">
                        <i class="fas fa-trash-alt text-danger"></i>
                    </a>
               </td>
            </tr>
        @endforeach
    </tbody>
    </table>
</div>

@include('branchleads.partials._branchleadmodal')
@include('branchleads.partials._mylead')
