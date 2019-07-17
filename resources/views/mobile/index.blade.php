@extends('site.layouts.default')
@section('content')
<h2>Mobile View</h2>
<h4>{{$branch->branchname}}</h4>

    <a href="{{route('mobile.show','activities')}}"  class="btn btn-primary  mr-1">Open Activities: ({{$branch->openactivities}})</button>
    <a href="{{route('mobile.show','leads')}}" class="btn btn-warning  mr-1">Open Leads ( {{$branch->leads_count}}) </a>
    <a href="{{route('mobile.show','opportunities')}}" class="btn btn-success  mr-1">Open Opportunities ({{$branch->open}})</a>

@include('mobile.partials._search')
@if(isset($results))
    @if($type=='activities')
        @include('mobile.partials._activities')
    
    @elseif ($type== 'leads')
        @include('mobile.partials._leads')
    @elseif ($type == 'opportunities')
        @include('mobile.partials._opportunities')
    @endif


@endif
@include('partials._scripts')
@endsection