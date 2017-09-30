@extends ('layouts.app')

@section('content')
<div class="container">
<h2>Create Permission!!</h2>
<div class="form-group">
<form method="post" name="createPermission" action="{{route('permissions.store')}}">
{{csrf_field()}}

@include('permissions.partials.form')

<input type="submit" class="btn btn-success" value="Create Permission" />
</form>

</div>

</div>


@endsection
