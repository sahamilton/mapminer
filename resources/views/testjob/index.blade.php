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
    

    <input class="btn btn-info" type="submit" value="Test Job">
</form>

@endsection
