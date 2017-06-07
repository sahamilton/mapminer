@component('mail::message')

## New Leads 

{{$team->firstname}}, 

{!! $data['message'] !!}

@component('mail::button', ['url' => route('salesleads.index'), 'color' => 'blue'])
        Check out your {{$data['source']->title}} sales leads and resources.
@endcomponent

<em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('salesleads.index')}}]({{ route('salesleads.index')}}) </em>

Sincerely
        
{{env('APP_NAME')}}
@endcomponent