@component('mail::message')

## New Leads 

{{$manager->firstname}}, 

{!! $data['message'] !!}

@component('mail::button', ['url' => route('branchleads.show',$branch->id), 'color' => 'blue'])
        Check out your {{$leadsource->title}} leads and resources.
@endcomponent

<em> If you’re having trouble clicking the  button, copy and paste the URL below
into your web browser: [{{ route('branchleads.show',$branch->id)}}]({{ route('branchleads.show',$branch->id)}}) </em>

Sincerely
        
{{env('APP_NAME')}}
@endcomponent