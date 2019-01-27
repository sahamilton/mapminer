@extends ('admin.layouts.default')
@section('content')
<div class="container">
 
   <h2>{{$leadsource->source}} Unassigned Leads</h2>

    <p>There are {{$leadsource->unassigned}} leads from this list of a total {{$leadsource->address_count}}.</p>
<a href="{{route('leadsource.assign',$leadsource->id)}}" class="btn btn-info">Assign Geographically</a>
</div>

@include('partials._scripts')
@endsection
