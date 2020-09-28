@extends('site.layouts.default')
@section('content')
@php $totals = []; @endphp
<div class="container">
   <h2>{{$campaign->title}} Company Summary</h2>
   <p><a href="{{route('campaigns.track', $campaign->id)}}">Show Branch Stats</a></p>
    <p>
        <a href="{{route('campaigns.company.export', $campaign->id)}}">
            <i class="fas fa-download"></i>
                Export to Excel
        </a>
    </p>
    @php $route = 'campaigns.companyreport'; @endphp
    @if($team) 
        @include('campaigns.partials._teamselector')
    @endif
    
    @if (auth()->user()->hasRole(['admin', 'sales_operations']))
        <p>
            <a href="{{route('campaigns.index')}}">Return to all campaigns</a>
        </p>
        
    @endif
                    
            @include('campaigns.partials._campaignselector')
            @include('campaigns.partials._campaigncompanysummarytable')
    
</div>
@include('partials._scripts')
@endsection