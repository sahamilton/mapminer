@extends('site.layouts.default')
@section('content')
<div class="container">
    <h4>{{$branch->branchname}} {{parseCamelCase($metric)}}</h4>
    <p><a href="{{route('dataquality.index')}}">Return to all data quality metrics.</a></p>
    <table class="table table-striped" 
            id = "sorttable">
        <thead>
            <th>Businessname</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>ZIP</th>
            <th>Date Created</th>
            <th>Last Activity</th>

        </thead>
        <tbody>
            @foreach ($data as $address)
            <tr>
                <td>
                    <a href="{{route('address.duplicates', $address->address->id)}}">
                        {{$address->address->businessname}}
                    </a>
                </td>
                <td>{{$address->address->street}}</td>
                <td>{{$address->address->city}}</td>
                <td>{{$address->address->state}}</td>
                <td>{{$address->address->zip}}</td>
                <td>{{$address->created_at->format('Y-m-d')}}</td>
                <td>{{$address->lastactivity->count() ? $address->lastactivity->first()->activity_date->format('Y-m-d') : ''}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('partials._scripts')
@endsection