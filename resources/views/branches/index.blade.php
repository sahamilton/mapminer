@extends('site.layouts.default')
@section('content')
@if (auth()->user()->hasRole('admin'))
<div class="float-right">
<a href="{{{ route('branches.create') }}}" class="btn btn-small btn-info btn-success iframe">

<i class="fas fa-plus-circle " aria-hidden="true"></i>

 Create New Branch</a>  </div>
@endif

@if(isset($state))
    <livewire:branch-table :state='$state' />
@else
    <livewire:branch-table />
@endif

@include('partials._scripts')
@include('partials._modal')
@endsection
