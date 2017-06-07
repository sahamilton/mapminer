@component('mail::message')

## New Leads Assigned to your team

{{$manager['firstname']}};

The following message has been sent to these members of your team:
<ul>
@foreach ($manager['team'] as $key=>$value)
	<li>{{$value}}</li>
	
@endforeach
</ul>
@component('mail::panel')
{!! $data['message'] !!}

@component('mail::button', ['url' => route('salesleads.index'), 'color' => 'blue'])
        Check out your teams leads.
@endcomponent

<em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('salesleads.index')}}]({{ route('salesleads.index')}}) </em>
@endcomponent
Sincerely
        
{{env('APP_NAME')}}
@endcomponent