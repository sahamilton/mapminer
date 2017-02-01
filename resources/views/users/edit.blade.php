@extends ('layouts.app')

@section('content')
<div class="container">
<h2>Edit Quote</h2>
<div class="form-group">
<form method="post" name="editQuote" action="{{route('users.update',$user->id)}}">
<input type="hidden" name="_method" value="PUT" />
{{csrf_field()}}

@include('users.partials.form')

<input type="submit" class="btn btn-success" value="Edit Quote" />
</form>

</div>

</div>


@endsection
