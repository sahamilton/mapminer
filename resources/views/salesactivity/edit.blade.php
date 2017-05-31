@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Edit Sales Campaign</h2>
<div class="form-group">
<form method="post" name="editQuote" action="{{route('salesactivity.update',$activity->id)}}">
<input type="hidden" name="_method" value="PUT" />
{{csrf_field()}}

@include('salesactivity.partials._form')

<input type="submit" class="btn btn-success" value="Edit Sales Campaign" />
</form>

</div>

</div>


@endsection
