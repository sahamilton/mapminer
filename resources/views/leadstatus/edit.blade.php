@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Edit Lead Status</h2>
<div class="form-group">
<form method="post" name="editLeadStatus" action="{{route('leadstatus.update')}}" >
{{csrf_field()}}

@include('leadstatus.partials._form')

<input type="submit" class="btn btn-success" value="Edit Lead Status" />
</form>

</div>

</div>

@include('partials._scripts')
@endsection
