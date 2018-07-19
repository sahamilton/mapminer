@extends ('admin.layouts.default')
@section('content')
<div class="container">
    <h2>{{$branch->branchname}} Branch Leads</h2>
    <p><a href="">Return to all branches summary</a></p>
    @php $leads = $branch->leads; @endphp
    @include('leads.partials._tablist')
</div>
@include('partials._scripts')
@endsection
