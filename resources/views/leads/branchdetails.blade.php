@extends ('admin.layouts.default')
@section('content')
<div class="container">
    <h2>{{$branch->branchname}} Branch Leads</h2>
    @php $leads = $branch->leads; @endphp
    @include('leads.partials._tablist')
@include('partials._scripts')
@endsection
