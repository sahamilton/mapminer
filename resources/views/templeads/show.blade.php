@extends ('site.layouts.default')
@section('content')
<div class="container">
  <div class="col-md-10 col-md-offset-1">
    <h2>{{$person->postName()}} Leads</h2>
    @if($person->reportsTo)
    <h4>Reports To:<a href="{{route('salesrep.newleads',$person->reportsTo->id)}}">{{$person->reportsTo->postName()}}</a></h4>
    @endif
<p><a href='{{route("salesrep.newleads.map",$person->id)}}'>

 <i class="far fa-flag" aria-hidden="true"></i> Map view</a></p>
<p><a href="{{route('newleads.export', $person->id)}}"><i class="far fa-file-excel-o" aria-hidden="true"></i> Export to Excel </a>
</p>
  <div class="float-right">
    <a href="{{route('leads.create')}}" class="btn btn-info">Add Personal Lead</a>
  </div>
 <nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
     
        <a class="nav-item nav-link active" data-toggle="tab" href="#open">

          <strong>Open Leads ({{$openleads->count()}})</strong>
        </a>
    


    
        <a class="nav-item nav-link" data-toggle="tab" href="#closed">

          <strong>Closed Leads ({{$closedleads->count()}})</strong>
        </a>
  
      
</div>
   
<div class="tab-content">
    <div id="open" class="tab-pane fade show active">
        @include('templeads.partials._tabopenleads')
    </div>
    <div id="closed" class="tab-pane fade">
        @include('templeads.partials._tabclosedleads')
    </div>

    </div>
</div>
</nav>
@include('partials._scripts')
@endsection
