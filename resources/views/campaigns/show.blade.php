@extends('admin.layouts.default')
@section('content')
<div class="container">
	
	@if($campaign->status == 'planned')
		<livewire:campaign-summary :campaign_id='$campaign->id' />
	@else
		<livewire:campaign-tracking :campaign_id='$campaign->id' />
	@endif
</div>
@endsection()