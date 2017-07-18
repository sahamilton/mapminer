@component('mail::message')

{!! $data['message'] !!}

Sincerely
        
{{env('APP_NAME')}}
@endcomponent