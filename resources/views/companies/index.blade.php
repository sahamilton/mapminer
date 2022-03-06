@extends('site/layouts/default')
@section('content')

	@livewire('company-table')

@include('partials/_modal')
@include('partials/_scripts')
@endsection
