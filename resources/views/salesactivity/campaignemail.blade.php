@component('mail::message')

## {{$data['activity']->title}}

{{$data['sales']->firstname}}, 

{!! $data['message'] !!}

@component('mail::button', ['url' => route('salesactivity.show',$data['activity']->id), 'color' => 'blue'])
        Check out the {{$data['activity']->title}} campaign resources.
@endcomponent

<em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('salesactivity.show',$data['activity']->id)}}]({{ route('salesactivity.show',$data['activity']->id)}}) </em>

Sincerely
        
{{env('APP_NAME')}}
@endcomponent