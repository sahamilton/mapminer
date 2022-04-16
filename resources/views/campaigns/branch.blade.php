@extends('site.layouts.default')
@section('content')
<div class="container">
  
    @if(count($myBranches) > 1 && ! isset($branch))
        @livewire('campaign-tracking')
    @else
        @livewire('branch-campaign', ['campaign_id'=>$campaign->id,'branch_id'=>$branch->id])
    @endif
</div>

@endsection