@extends('site.layouts.default')
@section('content')
<div class="container">


    <div class="row">

        @livewire('opportunity-table')

    </div>
</div>
@include('partials._modal')
@include('partials._opportunitymodal')
@include('opportunities.partials._closemodal')

@include('partials._scripts')
@endsection