@extends('site.layouts.default')
@section('content')
<div class="container">
  
    @if(count(auth()->user()->person->getMyBranches()) > 1)
        @livewire('campaign-tracking')
    @else
        @livewire('branch-campaign')
    @endif
</div>

@endsection