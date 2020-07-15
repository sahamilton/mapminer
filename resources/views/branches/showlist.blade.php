@extends('site/layouts/default')
@section('content')
<h2>Branch {{$data['branch']->branchname}}  </h4>
<p>{{$data['branch']->fullAddress()}}</p>

<p><a href="{{ route('branches.index') }}">Show all branches</a></p>	
@php $data['type']='branch'; 
$data['fulladdress'] = $data['branch']->fullAddress();
@endphp

@include('maps.partials._form')

<p><a href="{{route('branches.show',$data['branch']->id)}}"><i class="far fa-flag" aria-hidden="true"></i>Map View</a></p>
<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
  <a class="nav-link nav-item active" 
      id="locations-tab" 
      data-toggle="tab" 
      href="#locations" 
      role="tab" 
      aria-controls="locations" 
      aria-selected="true">
    <strong> Nearby Locations</strong>
  </a>
    <a class="nav-item nav-link"  
        data-toggle="tab" 
        href="#team"
        id="team-tab"
        role="tab"
        aria-controls="team"
        aria-selected="false">

    <strong> Branch Team</strong>
  </a>
</div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div id="locations" class="tab-pane show active">
    @include('branches.partials._tablocations')
  </div>
  <div id="team" class="tab-pane fade">
 		@include('branches.partials._tabteam')
 	</div>
</div>


@include('partials/_scripts')


@endsection
