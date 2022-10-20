@extends('admin.layouts.default')
@section('content')
<div class="container">
	
	@if($campaign->status == 'planned')
		<livewire:campaign-summary :campaign='$campaign' />
	@else
		<livewire:campaign-tracking :campaign='$campaign' />
	@endif
</div>
@endsection()