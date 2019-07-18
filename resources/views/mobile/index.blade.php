@extends('site.layouts.mobile')
@section('content')
<h2>Mobile View</h2>
<h4>{{$branch->branchname}}</h4>

    <a href="{{route('mobile.show','activities')}}"  class="btn btn-primary  mr-1">Open Activities: ({{$branch->openactivities}})</button>
    <a href="{{route('mobile.show','leads')}}" class="btn btn-warning  mr-1">Open Leads ( {{$branch->leads_count}}) </a>
    <a href="{{route('mobile.show','opportunities')}}" class="btn btn-success  mr-1">Open Opportunities ({{$branch->open}})</a>



<div id="message" style="color:#F00">{{\Session::get('message')}}</div>
    <style>
      #map_canvas {
        width: 800px;
        height: 600px;
      
      }
    </style><div style="margin-top:20px">
@include('mobile.partials._search')

  
   
</div>

@if(isset($results))
    @if($type=='activities')
        @include('mobile.partials._activities')
    
    @elseif ($type== 'leads')
        @include('mobile.partials._leads')
    @elseif ($type == 'opportunities')
        @include('mobile.partials._opportunities')
    @else
        
    @endif
@else
<div id="map_canvas"></div>
@endif
@include('partials._maps')
@include('partials._scripts')
@endsection