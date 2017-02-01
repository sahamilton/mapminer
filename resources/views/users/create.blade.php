@extends ('layouts.app')

@section('content')
<div class="container">
<h2>Create Quote</h2>
<div class="form-group">
<form method="post" name="createQuote" action="{{route('users.store')}}">
{{csrf_field()}}

@include('users.partials.form')

<input type="submit" class="btn btn-success" value="Create Quote" />
</form>

</div>

</div>


@endsection
