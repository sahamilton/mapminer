@if($location->currentcampaigns->count() >0)
    <p><strong>Participating in Current Campaign(s)</strong></p>
    @foreach ($location->currentcampaigns as $campaign)
        <li>{{$campaign->title}}</li>
    @endforeach
@else
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
