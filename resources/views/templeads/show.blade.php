@extends ('site.layouts.default')
@section('content')
<div class="container">
    <h2>{{$person->postName()}} Leads</h2>
    
<p><a href='{{route("salesrep.newleads.map",$person->id)}}'>
 <i class="fa fa-flag" aria-hidden="true"></i> Map view</a></p>

    <div class="col-md-10 col-md-offset-1">
        <ul class="nav nav-tabs">
      <li class="active">
        <a data-toggle="tab" href="#open">
          <strong>Open Leads ({{count($openleads)}})</strong>
        </a>
      </li>

      <li>
        <a data-toggle="tab" href="#closed">
          <strong>Closed Leads ({{count($closedleads)}})</strong>
        </a>
      </li>
      
     </ul> 
   
  <div class="tab-content">
    <div id="open" class="tab-pane fade in active">
        @include('templeads.partials._tabopenleads')
    </div>
    <div id="closed" class="tab-pane fade in">
        @include('templeads.partials._tabclosedleads')
    </div>

    </div>
</div>
</div>
@include('partials._scripts')
@endsection
