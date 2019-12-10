@foreach ($views as $key=>$view)
@if($branch->$key)
# {{$view['title']}}

{{$view['detail']}}
@include('campaigns.emails.partials._'.$key)
@endif
@endforeach