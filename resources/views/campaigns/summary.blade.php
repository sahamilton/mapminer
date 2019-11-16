@extends('site.layouts.default')
@section('content')
@php $totals = []; @endphp
<div class="container">
   <h2>{{$campaign->title}} Summary</h2>
    <p>
        <a href="{{route('campaigns.export', $campaign->id)}}">
            <i class="fas fa-download"></i>
                Export to Excel
        </a>
    </p>

        <p>
            <a href="{{route('campaigns.index')}}">Return to all campaigns</a>
        </p>
        
            @include('campaigns.partials._teamselector')
        
            @include('campaigns.partials._campaignselector')
            @include('campaigns.partials._campaignsummarytable')
    
</div>
@include('partials._scripts')
@endsection