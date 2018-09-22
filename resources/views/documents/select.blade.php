@extends ('site.layouts.default')
@section('content')
<h1>Select Documents</h1>
<div class="form-group">
<form name="documentSelect" method='post' action ="{{route('documents.select')}}" >
{{csrf_field()}}
@include('documents.partials.selectors')


<input type="submit" class="btn btn-success" value="Add Document" />
</form>
</div>

@endsection