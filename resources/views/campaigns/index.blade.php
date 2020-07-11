@extends('admin.layouts.default')
@section('content')
<div class="container">
	<h2>Branch Sales Campaigns</h2>


	<div class="float-right">
   		<a href="{{route('campaigns.create')}}" class="btn btn-info">Create New Campaign</a>
   </div>

    
    <div class="tab-content">
        
          
        @include('campaigns.partials._list')

        </div>
    </div>

@include('partials._modal')
@include ('partials._scripts')
@endsection()