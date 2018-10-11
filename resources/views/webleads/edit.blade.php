@extends('admin/layouts/default')

@section('content')
<div class="contianer">
	<div class="page-header">
		<h3>Edit Web Lead</h3>
	</div>

	<form method="post" action="{{route('webleads.update', $weblead->id)}}">
		{{csrf_field()}}
		<input type="hidden" name="_method" value="patch" />

		@include('webleads.partials._form')
		<div class="row">
			<div class="col-md-6 offset-md-4 pull-right">
			<input type="submit" class="btn btn-info" name="submit" value="Edit Web Lead" />
		</div>
		</div>
	</form>
</div>
@endsection
