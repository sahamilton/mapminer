@component('mail::message')
# Emails Sent

The following message has been sent:
@component('mail::panel')
       ###Subject:{{ $data['subject']}}

Example body: {!! $data['html'] !!}

@endcomponent
Sent to {{count($data['participants'])}} recipients.


Sincerely

{{ config('app.name') }}
@endcomponent
