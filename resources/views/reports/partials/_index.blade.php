<h2>All Reports</h2>
<div class="container">
    @can('manage_users')
    <div class="float-right">
        <a href="{{route('reports.create')}}" class="btn btn-info">Add Report</a>
    </div>
    @endcan
<table id ='sorttable8' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Report</th>
        <th>Description</th>
        @if(auth()->user()->hasRole('admin'))
        <th>Public</th>
        @endif
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach ($reports as $report)
        <tr>
            <td>
                @if(auth()->user()->hasRole('admin'))
                <a href="{{route('reports.show', $report->id)}}">
                    {{$report->report}}
                </a>
                @else
                    {{$report->report}}
                @endif
            </td>
           
            <td>{{ucwords($report->description)}}</td>            
             @if(auth()->user()->hasRole('admin'))
                <td>
                    @if($report->public)
                    Public
                    @endif
                </td>
             @endif
            <td>
               @if(auth()->user()->hasRole('admin'))
                    @include('reports.partials._actions')
                @else
                <a class="btn btn-success"
                    data-href="{{route('reports.run',$report->id)}}" 
                    data-toggle="modal" 
                    data-target="#run-report" 
                    data-title = "{{$report->report}}" 
                    href="#">
                    <i class="fas fa-file-download"></i>
                    Run Report
                    </a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

</div>
@if(! auth()->user()->hasRole('admin'))
    @include('reports.partials._variableselector')
@else
    @include('partials._modal')
@endif
