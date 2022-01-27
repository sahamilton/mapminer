@extends('admin.layouts.default')
@section('content')

<h2>Oracle HR Data</h2>

@livewire('oracle-table')
@include('partials/_modal')   
@include('partials/_scripts')
@endsection
