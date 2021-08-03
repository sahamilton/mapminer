@extends('admin.layouts.default')
@section('content')
<div class="container">

	@livewire('campaign-summary', ['campaign_id'=>$campaign->id])
</div>
@endsection()