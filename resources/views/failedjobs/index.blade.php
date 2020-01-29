@extends('admin.layouts.default')
@section ('content')
<div class="container">
    <h2>Failed Jobs</h2>
    <table id="sorttable"
    class="table table-striped"
    name="failedJobs">
        <thead>
            <th>Date</th>
            <th>Job</th>
        </thead>
        <tbody>
            @foreach ($jobs as $job)
            <tr>
                <td><a href="{{route('jobs.show', $job->id)}}">{{$job->failed_at->format('Y-m-d')}}</a></td>
                @php $data = json_decode($job->payload); @endphp
                
                <td>{{$data->displayName}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

