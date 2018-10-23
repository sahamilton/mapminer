@extends ('admin.layouts.default')
@section('content')
<div class="contianer">
<h2>Enter Lead to Distribute</h2>

@include('leads.partials._search')
<hr />
@include('leads.partials._cutandpaste')
</div>
@endsection
