@extends('admin.layouts.default')

@section('content')

<div class= 'container' style="padding-bottom:40px;">
<h2>Review Email</h2>

<p><strong>Subject:</strong> {!! $data['subject']!!}
<fieldset>
<p><strong>Body:</strong></p>
<p> {!!$data['html'] !!}</p>

</fieldset>

<p><strong>Recipient Count:</strong> {{$recipients->count()}}</p>
<form method='post' action="{{route('emails.send')}}" >
{{csrf_field()}}
<input type="hidden" name="id" value="{{$data['id']}}" />
<input type="hidden" name="message" value="{{$data['message']}}" />
<input type="submit" class="btn btn-danger" name="submit" alt="Send" value="Send"/>
<a class="btn btn-info" href="{{route('emails.show',$data['id'])}}">Edit Email</a>

</form>
</div>
@endsection()