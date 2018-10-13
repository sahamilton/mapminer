@extends ('admin.layouts.default')
@section('content')
<div class="container">
  <h2>Create an Email</h2>

  <!---- Tab message -->
  <ul class="nav nav-tabs">

    <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#home">Message</a></li>

    

  </ul>

  <div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      @include('emails.partials._tabmessage')
    </div>
    
  </div>
</div>
@include('emails.partials._scripts')

@endsection
