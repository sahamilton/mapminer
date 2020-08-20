@component('mail::message')

## New Leads 

{{$manager->firstname}}, 

{!! $data['message'] !!}

@component('mail::button', ['url' => route('branch.leads',$branch->id), 'color' => 'blue'])
        Check out your {{$leadsource->title}} leads and resources.
@endcomponent

<em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('branch.leads',$branch->id)}}]({{ route(branch.leads',$branch->id)}}) </em>

Sincerely
        
{{env('APP_NAME')}}
@endcomponent