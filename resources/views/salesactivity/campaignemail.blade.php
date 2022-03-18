@component('mail::message')

## {{$campaign->title}}

{{$manager->firstname}},

The {{$campaign->title}} has been launched.  This campaign runs from {{$campaign->datefrom->format('M j, Y')}} until {{$campaign->dateto->format('M j, Y')}}.

Branch {{$branch->branchname}} has {{$branch->leads_campaign}} leads available for the this campaign. 
@component('mail::button', ['url' => route('branchcampaign.show',[$campaign->id, $branch->id]), 'color' => 'blue'])
        Check out the {{$campaign->title}} campaign leads.
@endcomponent

<em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('branchcampaign.show',[$campaign->id, $branch->id])}}]({{ route('branchcampaign.show',[$campaign->id, $branch->id])}}) </em>

Sincerely
        
{{env('APP_NAME')}}
@endcomponent