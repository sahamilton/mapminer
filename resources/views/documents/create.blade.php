@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Add Sales Document to Library</h2>
<div class="form-group">
<form method="post" name="createDocument" action="{{route('documents.store')}}" enctype="multipart/form-data">
{{csrf_field()}}

@include('documents.partials._form')

<input type="submit" class="btn btn-success" value="Add Document" />
</form>

</div>

</div>

@include('partials._scripts')
@endsection
