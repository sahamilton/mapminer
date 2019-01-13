@extends ('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Branch Leads</h2>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Branch</th>
    <th>City</th>
    <th>State</th>
    <th>Manager</th>
    <th>Leads</th>


    </thead>
    <tbody>

 @foreach($branches as $branch)

    <tr>
        <td><a href="{{route('leads.branch',$branch->id)}}">{{$branch->branchname}}</a></td>
        <td>{{$branch->city}}</td>
        <td>{{$branch->state}}</td>
        <td>
            @foreach ($branch->manager as $manager)
                {{$manager->fullName()}}<br />
            @endforeach
        </td>
        <td>{{$branch->leads_count}}</td>
    </tr>
   @endforeach

    </tbody>
    </table>
</div>
@include('partials._scripts')
@endsection
