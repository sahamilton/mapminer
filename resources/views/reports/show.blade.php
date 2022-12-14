@extends('admin.layouts.default')
@section('content')
<h2>{{$report->report}}</h2>
<a class="btn btn-success"
    data-href="{{route('reports.run',$report->id)}}" 
    data-toggle="modal" 
    data-target="#run-report" 
    title="Send a copy of the report to yourself"
    data-title = "{{$report->report}}" 
    href="#">
    <i class="fas fa-file-download"></i>
    Run Report
    </a>
    @if($report->distribution->count()>0 && auth()->user()->hasRole('admin'))
<a class="btn btn-success"
    data-href="{{route('reports.send',$report->id)}}" 
    data-toggle="modal" 
    data-target="#run-report" 
    data-title = "{{$report->report}}" 
    href="#"
    title="Send report to the recipients">
    <i class="far fa-envelope"></i>
    Send Report
    </a>
    @endif
<p>
    @if(auth()->user()->hasRole('admin'))

    <a href="{{route('reports.edit',$report->id)}}">
        <i class="fas fa-pencil-alt"></i>Edit
    </a>
    @endif
</p>
<p><a href="{{route('reports.index')}}">Back to all reports</a></p>
@if($report->filename)
<p><a href="{{route('reports.review', $report->filename)}}">See stored {{$report->report}} files.</a></p>
@endif
<p>{{$report->description}}</p>
@if($report->object)
<p><label><strong>Model:</strong></label>{{ucwords($report->object)}}</p>
@endif
<p><label><strong>Job:</strong></label>\App\Jobs\{{ucwords($report->job)}}</p>
<p><label><strong>Export:</strong></label>\App\Exports\{{ucwords($report->export)}}</p>
<p><label><strong>Use Period Selector:</strong></label>
    @if($report->period == 1) Yes @else No @endif
</p>
<p>{!! $report->details !!}</p>
@if(auth()->user()->hasRole('admin'))

<div class="container">
    <div class="float-left" style="margin-bottom:10px">
        
            <form name="addRecipient" 
                method="post" 
                action="{{route('reports.addrecipient',$report->id)}}">
                @csrf
                <input class="form-control" type="email" name="email" placeholder="Valid Mapminer user email" />
                <input type="submit" class="btn btn-success" value="Add Recipient" />
            </form>
        
    </div>
    @include('reports.partials._distribution')


    @if($report->roledistribution->count() >0)
        @include('reports.partials._roleDistribution')
    @endif

    @if($report->companyDistribution->count() >0)
        @include('reports.partials._companydistribution')
    @endif
    </div>
    @include('reports.partials._removerecipient')
    @endif
    @include('reports.partials._variableselector')

@include('partials._scripts')
@endsection