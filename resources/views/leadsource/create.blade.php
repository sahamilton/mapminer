@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Create a New Prospect Source</h2>
<div class="form-group">
<form method="post" name="createLeadSource" action="{{route('leadsource.store')}}" >
{{csrf_field()}}

@include('leadsource.partials._form')

<input type="submit" class="btn btn-success" value="Add Lead Source" />
</form>

</div>

</div>

@include('partials._scripts')
@endsection
