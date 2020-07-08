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
            
            <a 
            data-pk="{{$lead->id}}"
            data-id="{{$lead->id}}"
            data-toggle="modal" 
            data-target="#addtocampaign" 
            data-title = "" 
            href="#">
                <i class="text-success fas fa-plus-circle"></i> Add to current campaign
            </a>
           
        </td>
        @endif
        <td>
            @if($lead->lastActivity)
                {{$lead->lastActivity->activity_date->format('Y-m-d')}}        
            @endif
        </td>


    </tr>
@endforeach