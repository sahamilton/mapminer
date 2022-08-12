@extends('site.layouts.default')
@section('content')


<livewire:branch-dashboard :branch='$branch' />
@include('partials._scripts')
@endsection
