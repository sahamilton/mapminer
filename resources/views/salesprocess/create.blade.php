@extends ('site.layouts.default')
@section('content')
<div class="container">
<h2>Create Process Step</h2>
<div class="form-group">
<form method="post" name="createStep" action="{{route('process.store')}}">
{{csrf_field()}}

@include('salesprocess.partials._form')

<input type="submit" class="btn btn-success" value="Create Sales Step" />
</form>

</div>

</div>


@endsection
