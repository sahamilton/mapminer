@extends ('admin.layouts.default')
@section('content')
<div class="container">
  <h2>Campaign Sales Team</h2>
  <h3>for the {{$activity->title}} campaign</h3>

  <h4>from {{$activity->datefrom->format('M j, Y')}} to {{$activity->dateto->format('M j, Y')}}</h4>
  <!---- Tab message -->
  <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">Message</a></li>
    <li><a data-toggle="tab" href="#menu1">Sales Team ({{$salesteam->count()}})</a></li>
    <li><a data-toggle="tab" href="#menu2">Modify Team</a></li>

  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      @include('salesactivity.partials._tabmessage')
    </div>
    <div id="menu1" class="tab-pane fade">
      @include('salesactivity.partials._tabteam')
    </div>
    <div id="menu2" class="tab-pane fade ">
      @include('salesactivity.partials._tabselectteam')
    </div>
  </div>
</div>
@include('partials._scripts')

@endsection
