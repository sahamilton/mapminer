@extends('site.layouts.default')

{{-- Content --}}
@section('content')
<div class="container">
<livewire:usertrack-table :managers='$managers' />

</div>


@endsection
