@if($address->currentcampaigns->count() >0)
    <p><strong>Participating in Current Campaign(s)</strong></p>
    @foreach ($address->currentcampaigns as $campaign)
        <li>{{$campaign->title}}
            <a 
            data-id="{{$campaign->id}}"
            data-toggle="modal" 
            
            data-target="#confirm-remove-campaign" 
            data-title = "remove {{$address->businessname}} from the {{$campaign->title}}" 
            href="#"
            title = "remove {{$address->businessname}} from the {{$campaign->title}}"> 
                <i class="far fa-trash-alt text-danger" ></i>
            </a>
        </li>
    @endforeach
    
@endif
@if($address->currentcampaigns->count() < $campaigns->count())
    <a 
    data-pk="{{$address->id}}"
    data-id="{{$address->id}}"
    data-toggle="modal" 
    data-target="#addtocampaign" 
    data-title = "" 
    href="#">
        <i class="text-success fas fa-plus-circle"></i> Add to current campaign
    </a>
@endif
