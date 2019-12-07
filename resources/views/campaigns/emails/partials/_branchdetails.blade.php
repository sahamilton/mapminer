@foreach ($views as $key=>$view)
@if($branch->$key)
# {{$view}}
@include('campaigns.emails.partials._'.$key)
@endif
@endforeach