@extends('site.layouts.default')
@section('content')

<div class="container">
    @livewire('usertrack-detail', ['person'=>$person])
</div>
@endsection