@extends ('site.layouts.default')
@section('content')
<div class="container">
<h2>Edit Sales Step</h2>
<div class="form-group">
<form method="post" name="editQuote" action="{{route('process.update',$process->id)}}">
<input type="hidden" name="_method" value="PUT" />
{{csrf_field()}}

@include('salesprocess.partials._form')

<input type="submit" class="btn btn-success" value="Edit Sales Step" />
</form>

</div>

</div>


@endsection
