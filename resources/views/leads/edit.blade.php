@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Edit Lead</h2>
<div class="form-group">
<form method="post" name="editLead" action="{{route('leads.update')}}" >
{{csrf_field()}}

@include('leads.partials._form')

<input type="submit" class="btn btn-success" value="Edit Lead" />
</form>

</div>

</div>

@include('partials._scripts')
@endsection
