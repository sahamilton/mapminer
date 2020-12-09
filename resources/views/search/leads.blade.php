@extends('site.layouts.default')

@section('content')
<div class="container">
<h2>Search My Leads</h2>

    @livewire('lead-table', ['myBranches'=> auth()->user()->person->myBranches()]);

</h2>


@endsection