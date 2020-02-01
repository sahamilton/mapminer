@extends('site.layouts.default')
@section('content')
<div class="container">
    <h4>{{$branch->branchname}} {{parseCamelCase($metric)}}</h4>
    <form 
        name="dataQualityDetails"
        method="post"
        action = "{{route('dataquality.details')}}">
        @csrf
        <div class="form-group">

            <select name="branch"  onchange="this.form.submit()">
            @foreach ($branches as $id=>$branchname)
            <option @if(isset($branch) && $branch->id == $id) selected @endif value="{{$id}}">{{$branchname}}</option>
            @endforeach
            </select>
        </div>
        <input 
            type="hidden" 
            name="metric" 
            value = "{{$metric}}" />
    </form>
    <p><a href="">Return to all metrics</a></p>
    <table class="table table-striped" 
        id = "sorttable">
        <thead>
            <th>Businessname</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>ZIP</th>

        </thead>
        <tbody>
           
            @foreach ($data as $address)
            <tr>
                <td>
                    <a href="{{route('address.duplicates', $address->id)}}">
                        {{$address->businessname}}
                    </a>
                </td>
                <td>{{$address->street}}</td>
                <td>{{$address->city}}</td>
                <td>{{$address->state}}</td>
                <td>{{$address->zip}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('partials._scripts')
@endsection
