@extends('site/layouts/default')


{{-- Page content --}}
@section('content')
<div class="container">
    <h2>How to sell to {{$company->companyname}}</h2>
@if(auth()->user()->hasRole('admin'))
<div class="float-right">
    <a href="{{route('salesnotes.edit', $company->id)}}" class="btn btn-info" value="Create / Edit" >Create / Edit </a>
</div>

@endif
@include('salesnotes.partials._shownote')
</div>
@include('partials._scripts')
@endsection

