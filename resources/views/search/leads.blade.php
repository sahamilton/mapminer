@extends('site.layouts.default')

@section('content')
<div class="container">
<h2>Search My Leads</h2>

    @livewire('lead-table', ['branch'=>$branch->id]);

</h2>


@endsection