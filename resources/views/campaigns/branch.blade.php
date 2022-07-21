@extends('site.layouts.default')
@section('content')
<div class="container">
  
    @if(count($myBranches) > 1 && ! isset($branch))
        <livewire:campaign-tracking />
    @elseif (isset($branch))
         <livewire:branch-campaign :campaign_id='$campaign->id' :branch_id='$branch->id' />'
    @else
        <livewire:branch-campaign :campaign_id='$campaign->id' />
    @endif
</div>

@endsection