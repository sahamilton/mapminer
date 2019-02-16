@extends ('site.layouts.default')
@section('content')
<div class="container">
    <h2>Branch Leads</h2>
    @include('branchleads.partials._tabsummary')
</div>
@include('partials._scripts')
@endsection
