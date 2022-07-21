@extends('admin.layouts.default')
@section('content')
<div class="container">
	

	<div class="float-right">
   		<a href="{{route('campaigns.create')}}" class="btn btn-info">Create New Campaign</a>
   </div>

   <livewire:campaign-table />
    

@include('partials._modal')
@include ('partials._scripts')
@endsection()