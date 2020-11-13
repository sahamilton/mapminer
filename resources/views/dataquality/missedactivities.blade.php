@extends('site.layouts.default')
@section('content')
<div class="container">
    <h4>{{$branch->branchname}} {{parseCamelCase($metric)}}</h4>
    <p><a href="{{route('dataquality.index')}}">Return to all data quality metrics.</a></p>
    
    <table class="table table-striped" 
            id = "sorttable">
        <thead>
            <th>Date Created</th>
            <th>Open Activity</th>
            <th>Follow Up Date</th>
            <th>Businessname</th>
            <th>Address</th>
            <th>City</th>
            <th>State</th>
            <th>ZIP</th>
     
        </thead>
        <tbody>
            @foreach ($data as $activity)
            <tr>
                <td>{{$activity->created_at->format('Y-m-d')}}</td>
                <td>
                    <a href="{{route('activity.show', $activity->id)}}">
                        {{$activity->type->activity}}
                    </a>
                </td>
                <td>{{$activity->followup_date->format('Y-m-d')}}</td>
                <td>
                    <a href="{{route('address.show', $activity->relatesToAddress->id)}}">
                        {{$activity->relatesToAddress->businessname}}
                    </a>
                </td>
                <td>{{$activity->relatesToAddress->street}}</td>
                <td>{{$activity->relatesToAddress->city}}</td>
                <td>{{$activity->relatesToAddress->state}}</td>
                <td>{{$activity->relatesToAddress->zip}}</td>

            </tr>
            @endforeach
        </tbody>
    </table>


</div>
@include('partials._scripts')
@endsection