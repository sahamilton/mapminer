@extends('admin.layouts.default')
{{-- Web site Title --}}
@section('title')
	
@endsection

{{-- Content --}}
@section('content')
	<div class="page-header">
		

		@livewire('user-table');	
	</div>
    
@include('partials/_modal')   
@include('partials/_scripts')

@endsection
