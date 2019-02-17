@component('mail::message')

## New Leads Notification Sent


The following message has been sent to {{count($data['branches'])}} branches and their managers:

@component('mail::panel')
{!! $data['message'] !!}

@component('mail::button', ['url' => route('leadsource.show',$leadsource->id), 'color' => 'blue'])
        Check out the {{$leadsource->source}} campaign resources.
@endcomponent

<em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('leadsource.show',$leadsource->id)}}]({{ route('leadsource.show',$leadsource->id)}}) </em>
@endcomponent
Sincerely
        
{{env('APP_NAME')}}
@endcomponent