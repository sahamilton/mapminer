@extends('site/layouts/default')


{{-- Page content --}}
@section('content')
<div class="container">
    <h2>Sales Notes for {{$company->companyname}}</h2>
    <p><a href="{{ route('company.show', $company->id) }}">See all locations of {{$company->companyname}}</a></p>
@if(auth()->user()->hasRole('admin'))
<div class="float-right">
    <a href="{{route('salesnotes.edit', $company->id)}}" class="btn btn-info" value="Create / Edit" >Create / Edit </a>
</div>

@endif
@include('salesnotes.partials._shownote')
</div>
@include('partials._scripts')
@endsection

