@extends('site.layouts.default')
@section('content')
@if (auth()->user()->hasRole('admin'))
<div class="float-right">
<a href="{{{ route('branches.create') }}}" class="btn btn-small btn-info btn-success iframe">

<i class="fas fa-plus-circle " aria-hidden="true"></i>

 Create New Branch</a>  </div>
@endif

<h1>All Branches</h1>


<?php $route ='branches.state';?>

<p><a href="{{route('branches.map')}}"><i class="far fa-flag" aria-hidden="true"></i>Map View</a>


@include('maps.partials._form')
@livewire('branch-table')

@include('partials._scripts')
@include('partials._modal')
@endsection
