@extends ('admin.layouts.default')
@section('content')
	@livewire('leadsource-show', ['leadsource'=>$leadsource]);
@endsection
