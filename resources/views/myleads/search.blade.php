@extends('site.layouts.default')
@section('content')

<h1>Leads Search</h1>
<h4>Containing the search term "<i class= "text text-danger">{{$search}}</i>"</h4>
<p><a href="{{route('branch.leads')}}">See all my leads</a></p>
<div class="row float-right" style="padding-bottom:10px">

	<!-- <a href="{{route('myleads.create')}}" class="btn btn-info">
		<i class="fas fa-plus-circle"></i>
			Add A Lead
	</a>-->
	<a class="btn btn-info" 
        title="Add Lead"
        data-href="" 
        data-toggle="modal" 
        data-target="#add_lead" 
        data-title = "Add lead" 
        href="#">
        <i class="fas fa-plus-circle " aria-hidden="true"></i>
        
        Add Lead
        </a>
</div>
<div class="row">
        @include('maps.partials._form')
</div>

<livewire:lead-table :search='$search' :branch='$branch' />
@include('myleads.partials._mylead')
  
@include('partials._scripts')
@endsection
