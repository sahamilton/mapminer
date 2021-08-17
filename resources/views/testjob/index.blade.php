@extends('site.layouts.maps')
@section('content')
<form id="jobForm"
    method='post'
    action = '{{route('testjob.store')}}'>
    @csrf
    <div class="form-group col-lg-2">
        <label>Job</label>
        <select id="job" 
        name="job" 
        class="form-control">
            @foreach ($jobs as $job)
                <option value="{{str_replace("\App\\", "", $job)}}">{{str_replace("\App\\", "", $job)}}</option>

            @endforeach
        </select>
    </div>
    <div class="form-group form-group-lg">
    <label for='fromdate'>From:</label>
    <input class="form-control" 
        type="date" 
        required 
        name="fromdate"  
        
        value="{{  old('fromdate', \Carbon\Carbon::now()->subMonths(1)->format('m/d/Y')) }}"/>
    <span class="help-block">
        <strong>{{$errors->has('fromdate') ? $errors->first('fromdate')  : ''}}</strong>
    </span>
</div>

<div class="form-group form-group-lg">
    <label for='todate'>To:</label>
    <input class="form-control" 
        type="date" 
        name="todate" 
        required 
        value="{{  old('todate', \Carbon\Carbon::now()->format('m/d/Y')) }}"/>
    <span class="help-block">
        <strong>{{$errors->has('todate') ? $errors->first('todate')  : ''}}</strong>
    </span>
</div>

<div class="form-group form-group-lg">
    <label for='manager'>Manager:</label>
    <select class="form-control" 
       
        name="manager" 
        
        id="manager" 
        value="{{  old('manager')}}">
        <option value="">All Managers
        </option>
        @foreach ($managers as $manager)
        <option value="{{$manager->id}}">{{$manager->fullName()}}
        </option>
        @endforeach
    </select>
    <span class="help-block">
        <strong>{{$errors->has('manager') ? $errors->first('manager')  : ''}}</strong>
    </span>
</div>

    <input class="btn btn-info" type="submit" value="Test Job">
</form>

@endsection
