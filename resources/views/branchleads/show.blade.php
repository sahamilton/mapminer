@extends ('admin.layouts.default')
@section('content')
<div class="container">
    <h2>{{$branch->branchname}} Branch Leads</h2>
    <p><a href="{{route('branchleads.index')}}">Return to all branches summary</a></p>
    @php $leads = $branch->leads; @endphp
        <div class="row float-right"><button type="button" 
    class="btn btn-info float-right" 
    data-toggle="modal" 
    data-target="#add_lead">
      Add Lead
</button>
    @include('branchleads.partials._tablist')
</div>
@include('partials._scripts')
@endsection
