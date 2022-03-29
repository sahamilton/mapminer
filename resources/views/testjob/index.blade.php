@extends('site.layouts.maps')
@section('content')
<form id="jobForm"
    method='post'
    action = '{{route('testjob.store')}}'>
    @csrf
    <x-form-select name="job" label="Select Job" class="form-group col-lg-2" :options='$jobs' />
    
    <x-form-input type="date" name="fromdate" label="Date From" class="form-group form-group-lg" />
    <x-form-input type="date" name="todate" label="Date To" class="form-group form-group-lg" />
    <x-form-select name="manager" label="Manager" class="form-group col-lg-2" :options='$managers' />


    <input class="btn btn-info" type="submit" value="Test Job">
</form>

@endsection
