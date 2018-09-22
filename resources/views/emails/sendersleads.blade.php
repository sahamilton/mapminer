@component('mail::message')

## New Prospects Notification Sent


The following message has been sent to {{$data['count']}} members of the sales organization:

@component('mail::panel')
{!! $data['message'] !!}

@component('mail::button', ['url' => route('leadsource.show',$data['source']->id), 'color' => 'blue'])
        Check out the {{$data['source']->title}} campaign resources.
@endcomponent

<em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('leadsource.show',$data['source']->id)}}]({{ route('leadsource.show',$data['source']->id)}}) </em>
@endcomponent
Sincerely
        
{{env('APP_NAME')}}
@endcomponent