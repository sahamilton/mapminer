@extends('site.layouts.default')
@section('content')

<div class="container">

<h2>Construction Project</h2>
<p><a href="{{route('construction.index')}}">Return to all projects</a></p>


 <h4><p><strong>Project Title:</strong>{{$project['siteaddresspartial']}}</h4>


<p><strong>Address:</strong>

<blockquote>{{$project['siteaddress']}}
</blockquote>
<div class="row">
<p><strong>People Ready Status:</strong>


</div>
<ul class="nav nav-tabs">
  <li class="nav-item active"><a class="nav-link" data-toggle="tab" href="#showmap"><strong>Project Details</strong></a></li>

  <li class="nav-item"><a class="nav-link" a data-toggle="tab" href="#contacts"><strong>Project Contacts @if($project['companylinks'])({{count($project['companylinks'])}}) @endif</strong></a></li>
  <li class="nav-item"><a class="nav-link"  data-toggle="tab" href="#branches"><strong>Nearby Branches</strong></a></li>
  <li class="nav-item"><a class="nav-link"  data-toggle="tab" href="#notes"><strong>Project Notes </strong></a></li>

</ul>

  <div class="tab-content">
    <div id="showmap" class="tab-pane fade in active">
      @include('construct.partials._details')  
    </div>

    
    <div id="contacts" class="tab-pane fade">
       @include('construct.partials._companylist')
    </div>

    <div id="branches" class="tab-pane fade">
       @include('construct.partials._branches')
    </div>

    <div id="notes" class="tab-pane fade">
     Notes go here
    </div>


  </div>
</div>
@include('partials._modal')
@include('partials/_scripts')
@endsection
