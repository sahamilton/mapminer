@extends('admin.layouts.default')
@section('content')
<div>
<h1>All Location Notes</h1>
@include('notes.partials._table')    
</div>
@include('partials/_scripts')
@endsection
