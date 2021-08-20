@extends('site.layouts.default')
@section('content')


@livewire('activities-table', ['branch'=>$branch->id, 'myBranches'=>$myBranches])
@include('partials._scripts')

@endsection