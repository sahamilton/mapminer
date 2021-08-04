@extends('site.layouts.default')
@section('content')
@php $totals = []; @endphp
<div class="container">
   <h2>{{$campaign->title}} Summary</h2>
    @if($campaign->type != 'open')
    <p><a href="{{route('campaigns.company', $campaign->id)}}">Show Company Stats</a></p>
    @endif
    <p>
        <a href="{{route('campaigns.export', $campaign->id)}}">
            <i class="fas fa-download"></i>
                Export to Excel
        </a>
    </p>

    @if (auth()->user()->hasRole(['admin', 'sales_operations']))
        <p>
            <a href="{{route('campaigns.index')}}">Return to all campaigns</a>
        </p>
        <p><a href="{{route('campaigns.launch', $campaign->id)}}" class="btn btn-warning">Relaunch Campaign</a></p>
    @endif
    @php $route = 'branchcampaign.manager'; @endphp
    @if($team && $team->count()>1)
        @include('campaigns.partials._teamselector')
    @endif
    @include('campaigns.partials._campaignselector')
    @include('campaigns.partials._campaignsummarytable')
    
</div>
@include('partials._scripts')
@endsection