@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Create Sales Campaign</h2>
<div class="form-group col-md-6">
<form method="post" name="createStep" action="{{route('campaigns.store')}}">
{{csrf_field()}}

@include('campaigns.partials._form')

<input type="submit" class="btn btn-success" value="Create Sales Campaign" />
</form>

</div>

</div>

@include('campaigns.partials._scripts')
@endsection
