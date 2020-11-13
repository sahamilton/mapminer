@extends('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Branch Managers with Branch Assignments</h2>
        <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>Branch Manager</th>
            <th>Address</th>
            <th>Manager</th>
            <th>Branch</th>
            <th>Distance from Branch (miles)</th>
        </thead>
        <tbody>
            @foreach($data as $person)
                @if(isset($person['branches']))
                    @foreach ($person['branches'] as $branch)
                    <tr>
                        <td><a href="{{route('person.details',$person['id'])}}">{{$person['name']}}</a></td>
                        <td>{{$person['address']}}</td>
                        <td><a href="{{route('person.details',$person['manager_id'])}}">@if(isset($person['manager'])) {{$person['manager']}}@endif</a></td>
                        <td><a href="{{route('branches.show',$branch['id'])}}">{{$branch['branchname']}}</a></td>
                        <td class="text-right">{{number_format($branch['distance'],1)}}</td>
                    </tr>
                   @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
</div>
@include('partials._scripts')
@endsection