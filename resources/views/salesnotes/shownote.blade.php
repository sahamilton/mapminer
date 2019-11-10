@extends('site/layouts/default')


{{-- Page content --}}
@section('content')
<div class="container">
@include('salesnotes.partials._shownote')
</div>
@include('partials._scripts')
@endsection

