<h2>Admin Reports</h2>
<div class="container">
    <div class="float-right">
        <a href="{{route('reports.create')}}" class="btn btn-info">Add Report</a>
    </div>
<table id ='sorttable8' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Report</th>
        <th>Model</th>
        <th>Description</th>
        <th>Distribution</th>
        <th>Actions</th>
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
            <td>{{ucwords($report->description)}}</td>            
            <td>{{$report->distribution_count}}</td>
            <td>
                @if(auth()->user()->hasRole('admin'))
                    @include('reports.partials._actions')

                @endif

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>

@include('partials._modal')
