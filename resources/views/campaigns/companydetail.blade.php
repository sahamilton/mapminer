@extends('admin.layouts.default')
@section('content')
<div class="container">
    <h2>{{$company->companyname}}</h2>
    <h4>{{$campaign->title}} Campaign</h4>
    
    <p>Active from {{$campaign->datefrom->format('Y-m-d')}} to {{$campaign->dateto->format('Y-m-d')}}</p>
    @include('campaigns.partials._companycampaignsummarytable')
</div>
@endsection