@extends('admin.layouts.default')
{{-- Web site Title --}}
@section('title')
	{{{ $title }}} :: @parent
@endsection
 @include('partials/_modal')
{{-- Content --}}
@section('content')
	<div class="page-header">
		<h3>{{ $title }}</h3>
			
				
			@if($serviceline != 'All')
				<h6><a href="{{route('users.index')}}">See All Users</a></h6>
			@endif
			<div class="float-right">

				<a href="{{{ route('users.create') }}}" class="btn btn-small btn-info iframe">
				
<i class="fas fa-plus-circle " aria-hidden="true"></i>

 Create</a>

			</div>
		
	</div>
    <div class="row">
	   @livewire('user-table')
    </div>
    
@include('partials/_scripts')
@endsection
