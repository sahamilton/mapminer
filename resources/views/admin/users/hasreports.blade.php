@extends('admin.layouts.default')
@section('content')
<div class="container">
	<livewire:change-reporting :person = '$person' />
</div>
@endsection