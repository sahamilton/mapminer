@extends('site.layouts.maps')
@section('content')
<form id="myForm">
    <div class="form-group col-lg-2">
        <label>Country</label>
        <select id="country" name="country" class="form-control">
            <option value="CA">Canada</option>
            <option value="US">USA</option>
        </select>
    </div>
    <div class="form-group col-lg-2">
        <label>province</label>
        <select name="province" class="form-control" disabled>
            <option value="1">a province</option>
        </select>
    </div>

    <input type="submit">
</form>
@include('test.script')
@endsection
