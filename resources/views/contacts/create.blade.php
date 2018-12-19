@extends('site.layouts.default')
@section('content')
<h2>Create New Contact</h2>
<form name="createcontact" action="{{route('contacts.store')}}" method="post">
	@csrf
	@include('contacts.partials._contactform')
	<input type="submit" name="submit" class="btn btn-success" value="Create Contact" />
</form>
@endsection