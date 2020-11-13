@if($location->currentcampaigns->count() >0)
    <p><strong>Participating in Current Campaign(s)</strong></p>
    @foreach ($location->currentcampaigns as $campaign)
        <li>{{$campaign->title}}
            <a 
            data-id="{{$campaign->id}}"
            data-toggle="modal" 
            
            data-target="#confirm-remove-campaign" 
            data-title = "remove {{$location->businessname}} from the {{$campaign->title}}" 
            href="#"
            title = "remove {{$location->businessname}} from the {{$campaign->title}}"> 
                <i class="far fa-trash-alt text-danger" ></i>
            </a>
        </li>
    @endforeach
    
@endif
@if($location->currentcampaigns->count() < $campaigns->count())
    <a 
    data-pk="{{$location->id}}"
    data-id="{{$location->id}}"
    data-toggle="modal" 
    data-target="#addtocampaign" 
    data-title = "" 
    href="#">
        <i class="text-success fas fa-plus-circle"></i> Add to current campaign
    </a>
@endif
