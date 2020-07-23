@foreach($leads as $lead)

 <tr>
        <td>
            <a href="{{route('address.show',$lead->id)}}">
                {{$lead->businessname}}
            </a>
        </td>

        <td>{{$lead->street}}</td>
        <td>{{$lead->city}}</td>
        <td>{{$lead->state}}</td>
        <td>
            @if($lead->leadsource)
             {{$lead->leadsource->source}}
            @endif
        </td>
        @if ($branch->currentcampaigns->count())
        <td>
            @foreach ($lead->currentcampaigns as $campaign)
               
                   <li>{{$campaign->title}}</li>
               
            @endforeach
            @if(auth()->user()->hasRole('branch_manager'))
            <a 
                data-pk="{{$lead->id}}"
                data-id="{{$lead->id}}"
                data-toggle="modal" 
                data-target="#addtocampaign" 
                data-title = "" 
                href="#">
                <i class="text-success fas fa-plus-circle"></i> Add to current campaign
            </a>
           @endif
        </td>
        @endif
        <td>
            @if($lead->lastActivity)
                {{$lead->lastActivity->activity_date->format('Y-m-d')}}        
            @endif
        </td>
        <td>
            @if($lead->dateAdded)
                {{$lead->dateAdded->format('Y-m-d')}}
            @endif
        </td>
        @if(auth()->user()->hasRole(['branch_manager']))
        <td>
       
            
            <a 
                data-href="{{route('branchleads.destroy',$lead->assignedToBranch->where('id', $this->branch->id)->first()->pivot->id)}}" 
                data-toggle="modal" 
                data-target="#delete-lead" 
                data-title = "  {{$lead->businessname}} lead from your branch" 
                href="#"><i class="fas fa-trash-alt text-danger"></i>
            </a>  
        </td>
       @endif
    </tr>
@endforeach