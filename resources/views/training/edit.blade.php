@extends('admin.layouts.default')


@section('content')
<div class="container">
<h1>Create A Training Item</h1>

<form name="createtraining" method="post" action ="{{route('training.update',$training->id)}}">
@csrf
@method('put')
@include('training.partials._form')
<input type="submit" name ="submit" class="btn btn-success" value="Update Training Item" />


</form>
</div>
@include('partials._scripts')
@endsection
