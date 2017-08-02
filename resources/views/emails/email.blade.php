@component('mail::message')

{!! $data['html'] !!}

Sincerely
        
{{env('APP_NAME')}}
@endcomponent