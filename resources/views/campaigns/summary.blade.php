@extends('admin.layouts.default')
@section('content')
<div class="container">
   <h2>{{$campaign->title}} Summary</h2>
    @include('campaigns.partials._summary')
        <p><a href="{{route('campaigns.index')}}">Return to all campaigns</a></p>
    <table id="sorttable"
        name="branchsummary"
        class="table table-striped"
        >
        <thead>
            <th>Branch</th>
            <th>Branch Name</th>
            <th>Leads</th>
            <th>Activities</th>
            <th>New Opportunities</th>
            <th>Opportunities Open</th>
            <th>Opportunities Won</th>
            <th>Opportunities Lost</th>
            <th>Opportunities Won Value</th>
            <th>Opportunities Open Value</th>
        </thead>
        <tbody>
            @foreach ($branches as $branch)
            @if($branch->leads_count > 0)
            <tr>
                <td>{{$branch->id}}</td>
                <td>{{$branch->branchname}}</td>
                <td>{{$branch->leads_count}}</td>
                <td>{{$branch->activities_count}}</td>
                <td>{{$branch->opened}}</td>
                <td>{{$branch->open}}</td>
                <td>{{$branch->won}}</td>
                <td>{{$branch->lost}}</td>
                <td>${{number_format($branch->wonvalue,2)}}</td>
                <td>${{number_format($branch->openvalue,2)}}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
    
</div>
@include('partials._scripts')
@endsection