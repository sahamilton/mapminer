@extends('admin.layouts.default')
{{-- Content --}}
@section('content')
	<div class="page-header">
		<h2><i class="fas fa-sync"></i> Sync users to Oracle HR</h2>
		@foreach ($methods as $method=>$data)
			<a href="{{ route('users.sync.'.$method) }}">
                 <i class="{{$data['icon']}}"></i> {{$data['text']}}
            </a>
		@endforeach

	</div>
@endsection