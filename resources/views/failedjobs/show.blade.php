@extends('admin.layouts.default')
@section ('content')
<div class="container">
    <h2>Failed Job</h2>
    @php $data = json_decode($job->payload); @endphp
    <div class="card">
        <div class="card-header">
            <p><strong>Job:</strong> {{$data->displayName}}</p>
            <p><strong>Date:</strong> {{$job->failed_at->format('l jS M Y h:i a')}}
        </div>
        <div class="card-body">
            <p>{{$job->exception}}</p>
        </div>
    </div>
    

</div>
@endsection