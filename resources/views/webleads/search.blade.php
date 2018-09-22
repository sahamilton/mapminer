@extends ('admin.layouts.default')
@section('content')
<div class="contianer">
<h2>Enter Lead to Distribute</h2>


@include('webleads.partials._search')
<hr />
@include('webleads.partials._cutandpaste')
</div>
@stop

