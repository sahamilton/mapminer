@extends('admin.layouts.default')
@section('content')
<h2>All Batch Reports</h2>
<div class="container">
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Report</th>
        <th>Model</th>
        <th>Distribution</th>
        <th>Send Copy</th>
    </thead>
    <tbody>
        @foreach ($reports as $report)
        <tr>
            <td>
                <a href="{{route('reports.show', $report->id)}}">
                    {{$report->report}}
                </a>
            </td>
            <td>{{ucwords($report->object)}}</td>
            
            <td>{{$report->distribution_count}}</td>
            <td>
                @if(! $report->object)
                    <a 
                    href="#" 
                    class="btn btn-success"
                    data-href="{{route('reports.run',$report->id)}}" data-toggle="modal" 
                    data-target="#run-report" 
                    data-title = "{{$report->report}}" 
                    href="#"> Run Report
                    </a>
                </a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>
@include('reports.partials._periodselector')
@include('partials._scripts')
@endsection