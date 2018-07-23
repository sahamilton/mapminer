@extends ('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Assignable Leads</h2>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Company</th>
    <th>Business Name</th>
    <th>City</th>
    <th>State</th>
    <th>SalesReps</th>


    </thead>
    <tbody>

 @foreach($leads as $lead)
    @if(isset($data[$lead->id]))
    <tr>
    <td><a href="{{route('leads.show',$lead->id)}}">{{$lead->businessname}}</a></td>
    <td>{{$lead->companyname}}</td>
    <td>{{$lead->address->city}}</td>
    <td>{{$lead->address->state}}</td>
    <td>
        <ul style="list-style-type: none">
        @foreach ($data[$lead->id] as $rep)
            <li>{{$rep->fullName()}} <i>{{number_format($rep->distance,1)}} m</i></li>
        @endforeach
        </ul>
    </td>
    </tr>
    @endif
   @endforeach

    </tbody>
    </table>
</div>
    @include('partials._scripts')
@endsection
