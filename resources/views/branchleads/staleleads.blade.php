@extends ('site.layouts.default')
@section('content')
<div class="container">
    <h2>Branch Stale Lead Counts</h2>

    <table class="table table-striped" id ="sorttable" >
        <thead>
            <th>Branch</th>
            <th>Stale Leads</th>
        </thead>
        <tbody>
            @foreach ($branches as $branch)
            <tr>
                <td>{{$branch->branchname}}</td>
                <td>{{$branch->stale_leads_count}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('partials._scripts')
@endsection
