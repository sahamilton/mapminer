@extends('site.layouts.maps')
@section('content')
<h2>All Branches</h2>


<p>
    <a href='{{route("branches.index")}}'>
        <i class="fas fa-th-list" aria-hidden="true"></i> List view
    </a>
</p>

  @php $route ='branches.index';@endphp
  @include('branches/partials/_state')
  @include('maps.partials._form')  
  @include('partials._branchesmap')
  

    <div id="map" style="width: 800px; height: 600px"></div>

@endsection
