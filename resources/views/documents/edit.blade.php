@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Edit Sales Document</h2>
<div class="form-group">
<form method="post" name="editDocument" action="{{route('documents.update',$document->id)}}" enctype="multipart/form-data">
<input type="hidden" name="_method" value="PUT" />
{{csrf_field()}}

@include('documents.partials._form')

<input type="submit" class="btn btn-success" value="Edit Sales Document" />
</form>

</div>

</div>

@include('partials._scripts')
@endsection
