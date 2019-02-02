@extends('site.layouts.default')
@section('content')

<h2>Feedback</h2>
<div class="alert alert-warning">
	<p>Use this feedback to provide the Mapminer developers with suggestions to improve the usability and value of Mapminer. Note use the Help & Support tab at the bottom of the screen for support related issues or email <a href="mailto:{{config('mapminer.system_contact')}}">{{config('mapminer.system_contact')}}.</a>
	</p>
</div>
<form action = "{{route('comment.store')}}" method="post">
	@csrf
	@include('comments/partials/_form')
	<input type="submit" class="btn btn-info" name="submit" value="Send Feedback" />
</form>
@endsection
