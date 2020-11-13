@extends('site.layouts.default')
@section('content')
<div class="container">
    <h4>{{$branch->branchname}} {{parseCamelCase($metric)}}</h4>
    <p><a href="{{route('dataquality.index')}}">Return to all data quality metrics.</a></p>
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
                <td class="text-center">{{$opportunity->created_at->format('Y-m-d')}}</td>
                <td class="text-right">${{number_format($opportunity->value,0)}}</td>
                <td class="text-center">{{number_format($opportunity->created_at->diffInDays(now()),0)}}</td>
                <td class="text-center">{{$opportunity->address->address->activities->last() ? $opportunity->address->address->activities->last()->activity_date->format('Y-m-d') : ''}}</td>
            </tr>
            @endforeach
            <tfoot>
                <td>Count: </td>
                <td class="text-right">{{number_format($data->count(),0)}}</td>
                
                
                <td>Sum </td>
                <td class="text-right">${{number_format($data->sum('value'),2)}}</td>
                <td></td>
                <td></td>
            </tfoot>
        </tbody>
    </table>
</div>
@include('partials._scripts')
@endsection
