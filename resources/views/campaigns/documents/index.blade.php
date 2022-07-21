@extends('admin.layouts.default')
@section('content')
<div class="container">
    

    

    <livewire:campaign-documents-table />
    

@include('partials._modal')
@include ('partials._scripts')

@endsection()