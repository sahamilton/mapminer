@extends('admin.layouts.default')
@section('content')
<h2>All Batch Reports</h2>
<div class="container">
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Report</th>
        <th>Frequency</th>
        <th>Schedule</th>
        <th>Time</th>
        <th>Period From</th>
        <th>Period To</th>
        <th>Distribution</th>
        <th>Send Copy</th>
    </thead>
    <tbody>
        @foreach ($results as $report)
        <tr>
            <td>{{$report->report}}</td>
            <td>{{$report->frequency}}</td>
            <td>{{$report->schedule}}</td>
            <td>{{date('h:i A',strtotime($report->time))}}</td>
            <td>{{$report->periodFrom}}</td>
            <td>{{$report->periodTo}}</td>
            <td>
                <a href="{{route('reports.show', $report->id)}}">
                    {{$report->distribution_count}}
                </a>
            </td>
            <td>
                <a href="{{route('reports.send',$report->id)}}">
                    <button class="btn btn-success">Send Copy</button>
                </a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>
@include('partials._scripts')
@endsection