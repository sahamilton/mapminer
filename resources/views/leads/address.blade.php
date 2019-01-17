@extends('site.layouts.default')
@section('content')
<div class="container" style="margin-top:40px">
    <h1>Closest Sales Reps </h1>

    <h4>Maximum of {{$data['number']}} within {{$data['distance']}} miles of {{$data['address']}}</h4>
    @include('leads.partials.search')
    @if($people)
    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>Employee Id</th>
            <th>Sales Rep</th>
            <th>Role</th>
            <th>Industry</th>
            <th>Email</th>
            <th>Location</th>
            <th>Distance</th>
        </th>

        </thead>
        <tbody>
            @foreach($people  as $person)
               
                <tr> 
                    <td>{{$person->userdetails->employee_id}} </td>

                    <td><a href="route('salesorg.show',$person->id)}}"
                    title = "See {{$person->firstname}}'s sales coverage area">{{$person->firstname}} {{$person->lastname}}</a></td> 
                    <td>{{$person->userdetails->roles[0]->name}}</td>
                    <td>
                        @if(isset($person->industry))
                            <ul>
                           
                            @foreach ($person->industry as $key=>$industry)
                                <li>{{$industry}}</li>

                            @endforeach
                            </ul>
                        @endif
                    </td>
                    <td><a href="mailto:{{$person->email}}" title = "Email {{$person->firstname}} {{$person->lastname}}">{{$person->userdetails->email}}</a></td> 
                    <td>{{$person->city}},{{$person->state}}</td>
                    <td>{{number_format($person->distance,2)}}</td> 
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="alert alert-danger">
  <strong>Warning!</strong> No results within this range. Try enlarging your search.
</div>
    @endif
</div>
@include('partials._scripts')
@endsection