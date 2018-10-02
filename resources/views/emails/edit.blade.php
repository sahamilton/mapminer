@extends ('admin.layouts.default')
@section('content')
<div class="container">
  <h2>Edit Email</h2>

  <!---- Tab message -->
  <ul class="nav nav-tabs">
    <li class="nav-item active">
      <a class="nav-link" data-toggle="tab" href="#home">Message</a></li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#menu1">Recipients ({{$email->recipients->count())}})</a></li>
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#menu2">Select Recipients</a></li>

  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      @include('emails.partials._tabmessage')
    </div>
    <div id="menu1" class="tab-pane fade">
      @include('emails.partials._tabteam')
    </div>
    <div id="menu2" class="tab-pane fade ">
      @include('emails.partials._tabselectteam')
    </div>
  </div>
</div>
@include('emails.partials._scripts')
@include('partials._scripts')
@endsection
