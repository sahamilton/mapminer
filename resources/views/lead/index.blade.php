@extends('site.layouts.default')
@section('content')

<h1>My Leads</h1>

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
@include('lead.partials._tablist')
@include('lead.partials._mylead')
  
@include('partials._scripts')
@endsection
