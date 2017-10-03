@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Edit Prospect Source</h2>
<div class="form-group">
<form method="post" name="editLeadSource" action="{{route('leadsource.update',$leadsource->id)}}" >
{{csrf_field()}}
<input type="hidden" name="_method" value="put" />

@include('leadsource.partials._form')

<input type="submit" class="btn btn-success" value="Edit Prospect Source" />
</form>

</div>

</div>

@include('partials._scripts')
@endsection
