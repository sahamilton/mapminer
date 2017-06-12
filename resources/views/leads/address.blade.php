@extends('site.layouts.default')
@section('content')
<div class="container" style="margin-top:40px">
<h1>Closest Sales Reps </h1>

<h4>Maximum of {{$data['number']}} within {{$data['distance']}} miles of {{$data['address']}}</h4>
@include('leads.partials.search')
@if(count($people)>0)
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
        <th>Employee Id</th>
        <th>Sales Rep</th>
        <th>Role</th>
        <th>Email</th>
        <th>Location</th>
        <th>Distance</th>
    </th>

    </thead>
    <tbody>
        @foreach($people  as $person)

            <tr> 
                <td>{{$person->employee_id}}</td>

                <td><a href="{{route('salesorg',$person->id)}}"
                title = "See {{$person->firstname}}'s sales coverage area">{{$person->firstname}} {{$person->lastname}}</a></td> 
                <td>{{$person->role}}</td>
                <td><a href="mailto:{{$person->email}}" title = "Email {{$person->firstname}} {{$person->lastname}}">{{$person->email}}</a></td> 
                <td>{{$person->city}},{{$person->state}}</td>
                <td>{{number_format($person->distance_in_mi,2)}}</td> 
            </tr>
        @endforeach
    </tbody>
</table>
@endif
</div>
@include('partials._scripts')
@endsection