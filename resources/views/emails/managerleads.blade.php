@component('mail::message')

## New Leads Assigned to your team

{{$manager->fullName()}};

The following message has been sent to these members of your team:
<ul>
@foreach ($branches as $branch)
	<li>{{$branch->branchname}} <em>{{$branch->manager->first()->fullName()}}</em></li>
	
@endforeach
</ul>

@component('mail::panel')
{!! $data['message'] !!}

@component('mail::button', ['url' => route('opportunity.index'), 'color' => 'blue'])
        Check out your teams prospects.
@endcomponent

<em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('opportunity.index')}}]({{ route('opportunity.index')}}) </em>
@endcomponent
Sincerely
        
{{env('APP_NAME')}}
@endcomponent