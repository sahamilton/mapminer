@component('mail::message')

## {{$data['activity']->title}}

{{$manager['firstname']}};

The following message has been sent to these members of your team:
<ul>
@foreach ($manager['team'] as $key=>$value)
	<li>{{$value}}</li>
	
@endforeach
</ul>
@component('mail::panel')
{!! $data['message'] !!}

@component('mail::button', ['url' => route('salesactivity.show',$data['activity']->id), 'color' => 'blue'])
        Check out the {{$data['activity']->title}} campaign resources.
@endcomponent

<em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('salesactivity.show',$data['activity']->id)}}]({{ route('salesactivity.show',$data['activity']->id)}}) </em>
@endcomponent
Sincerely
        
{{env('APP_NAME')}}
@endcomponent