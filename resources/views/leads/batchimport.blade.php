@extends ('admin.layouts.default')
@section('content')
<div class="container">
<h2>Import Leads from Spreadsheet</h2>

<form method="post" name="createLead" action="{{route('leads.batch')}}" enctype="multipart/form-data">
{{csrf_field()}}

@include('leads.partials._batchform')

<div class="form-group">
<input type="submit" class="btn btn-success" value="Import Leads" />
</div>
</form>



</div>

@include('partials._scripts')
@endsection
