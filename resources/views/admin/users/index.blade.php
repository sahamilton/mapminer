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

			
			</div>
    <div class="row">
	   @livewire('user-table')
    </div>
    
@include('partials/_scripts')
@endsection
