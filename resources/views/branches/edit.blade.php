@extends('site.layouts.default')

@section('content')


<form method="post" name="updatebranch" action ="{{route('branches.update', $branch->id)}}" >
	@method('put')
	@csrf

	@include('branches.partials._form')
	    
	<input type="submit" class="btn btn-success" value="Edit Branch" />
</form>


@endsection
