@extends ('site.layouts.default')
@section('content')
<div class="container">
<h2>Create Sales Campaign</h2>
<div class="form-group">
<form method="post" name="createStep" action="{{route('salesactivity.store')}}">
{{csrf_field()}}

@include('salesactivity.partials._form')

<input type="submit" class="btn btn-success" value="Create Sales Campaign" />
</form>

</div>

</div>

@include('salesactivity.partials._scripts')
@endsection
