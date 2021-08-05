@extends('site.layouts.default')
@section('content')
<div class="container">
  
    @if(count(auth()->user()->person->getMyBranches()) > 1 && ! isset($branch))
        @livewire('campaign-tracking')
    @else
        @livewire('branch-campaign', ['branch_id'=>$branch->id])
    @endif
</div>

@endsection