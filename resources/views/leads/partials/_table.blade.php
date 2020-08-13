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
       
            
             
        </td>
       @endif
    </tr>
@endforeach