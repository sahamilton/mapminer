@extends('admin.layouts.default')

{{-- Page title --}}
@section('title')
Edit a Service Line::
@parent
@endsection
@section('content')
<div class="page-header">
    <h3>
        Edit Service Line

        <div class="float-right">
            <a href="{{ route('serviceline.index') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> Back</a>
        </div>
    </h3>
</div>

<!-- Tabs -->

@php $buttonLabel = 'Edit Service Line';@endphp

<form name="serviceline"
    method='post'
    action = "{{route('serviceline.update', $serviceline->id)}}"
    >
    @csrf
    @method="patch"
    @include('servicelines.partials._form')
</form>
</div>
@endsection
