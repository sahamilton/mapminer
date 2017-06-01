@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Create a New Lead Status</h2>
<div class="form-group">
<form method="post" name="createLeadStatus" action="{{route('leadstatus.store')}}" >
{{csrf_field()}}

@include('leadstatus.partials._form')

<input type="submit" class="btn btn-success" value="Add Lead Status" />
</form>

</div>

</div>

@include('partials._scripts')
@endsection
