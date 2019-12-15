@foreach ($views as $key=>$view)

# {{$view['title']}}

{{$view['detail']}}
@include('campaigns.emails.partials._'.$key)

@endforeach