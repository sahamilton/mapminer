@extends('admin.layouts.default')
@section('content')
<div class="container">
<h2>{{$email->subject}}</h2>
<p><a href="{{route('emails.index')}}">Return to all emails</a></p>
@if($email->sent)
<p>Sent {{$email->sent->format('M j, Y')}}</p>
@else
<p>Not sent</p>
@endif
<fieldset><legend>Email Message</legend>
<blockquote>{!!$email->message!!}</blockquote>
</fieldset>
<fieldset><legend>Recipients</legend>
@include('emails.partials._tabteam')
</fieldset>
</div>

@include('partials._scripts')
<<<<<<< HEAD
@stop
=======
@endsection
>>>>>>> development
