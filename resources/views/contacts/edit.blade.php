@extends('site.layouts.default')
@section('content')
<h2>Edit {{$contact->location->businessname}} Contact</h2>
<p>
    <a href="{{route('address.show', $contact->location->id)}}" 
        title = "Return to {{$contact->location->businessname}}">
        Return to {{$contact->location->businessname}}
    </a>
</p>
<form name="editcontact" action="{{route('contacts.update',$contact->id)}}" method="post">
	@csrf
	@method('put')
	@include('contacts.partials._contactform')
	<input type="submit" name="submit" class="btn btn-success" value="Edit Contact" />
</form>
@endsection