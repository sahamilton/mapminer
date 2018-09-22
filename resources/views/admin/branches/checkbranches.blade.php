@extends('admin.layouts.default')
@section('content')
<div class="container">
    <h2>People More than 100 Miles from Branch Assignments</h2>
        <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
            <th>Person</th>
            <th>Address</th>
            <th>Branch</th>
            <th>Branch Distance (miles)</th>
        </thead>
        <tbody>
            @foreach($data as $person)
                @if(isset($person['branches']))
                    @foreach ($person['branches'] as $branch)

                    
                    <tr>
                        <td><a href="{{route('person.details',$person['id'])}}">{{$person['name']}}</a></td>
                        <td>{{$person['address']}}</td>
                        
                        <td>{{$branch['branchname']}}</td>
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