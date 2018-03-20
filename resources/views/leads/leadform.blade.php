@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Import Web Leads</h2>
<div class="form-group">
<form method="post" name="editLead" action="{{route('leads.webleadsinsert')}}" >
{{csrf_field()}}

<textarea name="weblead">
	

</textarea>

<input type="submit" class="btn btn-success" value="Import Lead" />
</form>

</div>

</div>

@include('partials._scripts')
@endsection
