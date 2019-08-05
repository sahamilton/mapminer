@component('mail::message')

## Branch {{$branch->branchname}} Opportunities

{{$manager->firstname}} 

@if($branch->opportunitiesClosingThisWeek->count() > 0)
You have the following opportunities scheduled to close in the next seven days.
@component('mail::table')
Company      	|Opportunity Description  | Value  |Expected Close  |
| ----------------- | --------:| ------:| --------:|
@foreach ($branch->opportunitiesClosingThisWeek as $opportunity)
| <a href="{{route('opportunity.show',$opportunity->id)}}">{{$opportunity->address->address->businessname}} </a>| {{$opportunity->description}} | ${{$opportunity->value}} | {{$opportunity->expected_close->format('D jS M')}}  | 
@endforeach

@endcomponent
@endif

@if($branch->pastDueOpportunities->count() >0)

The following opportunities were scheduled to close before today but are still marked as open.
@component('mail::table')
Company         |Opportunity Description  | Value  |Expected Close  |
| ----------------- | --------:| ------:| --------:|
@foreach ($branch->pastDueOpportunities as $opportunity)
| <a href="{{route('opportunity.show',$opportunity->id)}}">{{$opportunity->address->address->businessname}} </a>| {{$opportunity->description}} | ${{$opportunity->value}} | {{$opportunity->expected_close->format('D jS M')}}  | 
@endforeach

@endcomponent

@endif
@component('mail::button', ['url' => route('opportunity.index'), 'color' => 'blue'])
        Check out all your branch opportunities.
@endcomponent

Sincerely
        
{{env('APP_NAME')}}
@endcomponent
