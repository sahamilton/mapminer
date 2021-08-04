@extends('admin.layouts.default')
@section('content')
<div class="container">

	@livewire('campaign-tracking', ['campaign_id'=>$campaign->id])
</div>
@endsection()