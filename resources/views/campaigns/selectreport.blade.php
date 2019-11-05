@extends('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Campaign Reporting</h2>
    @if($campaigns->count() > 1)
        @include('campaigns.partials._selector')
    @endif
    <form
        name="reportselector"
        id="reportselectorform"
        method="post"
        action="{{route('campaigns.report', $campaign->id)}}"
        >
        @csrf
        @include('campaigns.partials._reportselector')

        <input type="submit"
            name="submit"
            value="Generate" />
    </form>

</div>

@endsection