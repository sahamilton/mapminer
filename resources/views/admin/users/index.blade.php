@extends('admin.layouts.default')
{{-- Web site Title --}}
@section('title')
	
@endsection
 @include('partials/_modal')
{{-- Content --}}
@section('content')
	<div class="page-header">
		

		@livewire('user-table');	
	</div>
    
@include('partials/_scripts')
@endsection
