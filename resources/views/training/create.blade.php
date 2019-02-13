@extends('admin.layouts.default')


@section('content')

<h1>Create A Training Item</h1>

<form name="createtraining" method="post" action ="{{route('training.store')}}">
{{csrf_field()}}
@include('training.partials._form')
<input type="submit" name ="submit" class="btn btn-success" value="Create Training Item" />


</form>

@include('partials._scripts')
@endsection
