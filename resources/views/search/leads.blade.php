@extends('site.layouts.newdefault')

@section('content')
<div class="container">

    <livewire:lead-search :branch='$branch->id' />

@endsection