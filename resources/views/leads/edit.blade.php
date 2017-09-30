@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Edit Propsect</h2>
<div class="form-group">
<form method="post" name="editLead" action="{{route('leads.update',$lead->id)}}" >
{{csrf_field()}}
<input type="hidden" name="_method" value="put" />
@include('leads.partials._form')

<input type="submit" class="btn btn-success" value="Edit Prospect" />
</form>

</div>

</div>

@include('partials._scripts')
@endsection
