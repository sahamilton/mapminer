@extends('site.layouts.default')
@section('content')
<div class="container">
    <h4>{{$branch->branchname}} {{parseCamelCase($metric)}}</h4>
    <table class="table table-striped" id="sorttable">
        <thead>
            <th>Opportunity</th>
            <th>Company</th>
            <th>Opened</th>
            <th>Value</th>
            <th>Days Open</th>
            <th>Last Activity</th>
        </thead>
        <tbody>
            @foreach ($data as $opportunity)
     
            <tr>
                <td>
                    <a href="{{route('opportunity.show', $opportunity->id)}}">
                        {{$opportunity->title}}
                    </a>
                </td>
                <td>
                    <a href="{{route('address.show', $opportunity->address_id)}}">
                        {{$opportunity->address->address->businessname}}
                    </a>
                </td>
                <td>{{$opportunity->created_at->format('Y-m-d')}}</td>
                <td>${{number_format($opportunity->value,0)}}</td>
                <td>{{number_format($opportunity->created_at->diffInDays(now()),0)}}</td>
                <td>{{$opportunity->address->address->activities->last() ? $opportunity->address->address->activities->last()->activity_date->format('Y-m-d') : ''}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('partials._scripts')
@endsection
