@extends ('site.layouts.default')
@section('content')
<div class="container">
  <div class="col-md-10 col-md-offset-1">
    <h2>{{$person->postName()}} Leads</h2>
    @if($person->reportsTo)
    <h4>Reports To:<a href="{{route('salesrep.newleads',$person->reportsTO->id)}}">{{$person->reportsTo->postName()}}</a></h4>
    @endif
<p><a href='{{route("salesrep.newleads.map",$person->id)}}'>
<<<<<<< HEAD
 <i class="fa fa-flag" aria-hidden="true"></i> Map view</a></p>
<p><a href="{{route('newleads.export', $person->id)}}"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export to Excel </a>
</p>
  
   <ul class="nav nav-tabs">
      <li class="active">
        <a data-toggle="tab" href="#open">
=======
 <i class="far fa-flag" aria-hidden="true"></i> Map view</a></p>
<p><a href="{{route('newleads.export', $person->id)}}"><i class="far fa-file-excel-o" aria-hidden="true"></i> Export to Excel </a>
</p>
  
   <ul class="nav nav-tabs">
      <li class="nav-item active">
        <a class="nav-link active" data-toggle="tab" href="#open">
>>>>>>> development
          <strong>Open Leads ({{$openleads->count()}})</strong>
        </a>
      </li>

<<<<<<< HEAD
      <li>
        <a data-toggle="tab" href="#closed">
=======
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#closed">
>>>>>>> development
          <strong>Closed Leads ({{$closedleads->count()}})</strong>
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
