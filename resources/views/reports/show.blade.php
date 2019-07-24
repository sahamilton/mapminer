@extends('admin.layouts.default')
@section('content')
<h2>{{$report->report}}</h2>
<p><a href="{{route('reports.index')}}">Back to all reports</a></p>
<p>{{$report->description}}</p>
<p><label><strong>Model:</strong></label>{{ucwords($report->object)}}</p>
<div class="container">
    <div class="float-left" style="margin-bottom:10px">
        <fieldset><legend>Add recipient</legend>
            <form name="addRecipient" 
                method="post" 
                action="{{route('reports.addrecipient',$report->id)}}">
                @csrf
                <input class="form-control" type="email" name="email" placeholder="Valid Mapminer user email" />
                <input type="submit" class="btn btn-success" value="Add Recipient" />
            </form>
        </fieldset>
    </div>

@include('reports.partials._distribution')


@if($report->roledistribution->count() >0)
    @include('reports.partials._roleDistribution')
@endif

@if($report->companyDistribution->count() >0)
    @include('reports.partials._companydistribution')
@endif
</div>
@include('partials._scripts')
@endsection