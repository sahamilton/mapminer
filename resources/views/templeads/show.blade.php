@extends ('site.layouts.default')
@section('content')
<div class="container">
  <div class="col-md-10 col-md-offset-1">
    <h2>{{$person->fullName()}} Leads</h2>
    @if($person->reportsTo)
    <h4>Reports To:<a href="{{route('salesrep.newleads',$person->reportsTO->id)}}">{{$person->reportsTo->fullName()}}</a></h4>
    @endif
<p><a href='{{route("salesrep.newleads.map",$person->id)}}'>
 <i class="fa fa-flag" aria-hidden="true"></i> Map view</a></p>
<p><a href="{{route('newleads.export', $person->id)}}"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel </a>
</p>
  
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
