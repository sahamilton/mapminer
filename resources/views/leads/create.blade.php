@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Create a Single Lead</h2>

<form method="post" name="createLead" action="{{route('leads.store')}}" >
{{csrf_field()}}

@include('leads.partials._form')

<div class="form-group">
<input type="submit" class="btn btn-success" value="Add Lead" />
</div>
</form>



</div>

@include('partials._scripts')
@endsection
