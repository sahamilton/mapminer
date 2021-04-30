@extends('site/layouts/default')
@section('content')
@if(! isset($title))
@can('manage_accounts')
<h1>All Companies</h1>
<p><a href="{{route('nearbycompanies')}}"><i class="fas fa-drafting-compass"></i> See nearby company locations</p>
<p><a href="{{route('allcompanies.export')}}">
<i class="far fa-file-excel"></i>
Export to Excel</a></p>
@else
<h1>Companies That Have Locations Nearby<span class="text text-danger"><sup>*</sup></span>
</h1>
@endcan
@else
<h1>{{$title}}</h1>
<p><a href = "{{route('company.index')}}">Return to all companies</a></p>
@endif



@include('partials/_showsearchoptions')
@include('partials/advancedsearch')



@if (auth()->user()->hasRole('admin') or auth()->user()->hasRole('sales_operations'))



<div class="float-right">
<a href="{{ route('company.create') }}" class="btn btn-small btn-info iframe">

<i class="fas fa-plus-circle " aria-hidden="true"></i>

 Create New Account</a>
</div>
@endif

	@livewire('company-table')

@include('partials/_modal')
@include('partials/_scripts')
@endsection
