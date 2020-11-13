@extends('site.layouts.default')
@section('content')
<div class="container">

    <div class="row">

    @livewire('opportunity-table', ['branch'=>$branch])

    </div>
</div>


@include('opportunities.partials._closemodal')
@include('partials._opportunitymodal')


@include('partials._scripts')
@endsection
