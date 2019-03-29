@extends ('admin.layouts.default')
@section('content')
<h4>{{$leadsource->source}} Results</h4>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>

    <th>Branch</th>
    <th>Manager</th>
    <th>Leads</th>
    <th>Activities</th>
    <th>Opportunities</th>

    </thead>
    <tbody>
         @foreach($data as $branch)
        
            <tr>
                <td>{{$branch['branch']}}</td>
                <td>Manager</td> 
                <td>{{$branch['leads']}}</td>
                <td>{{$branch['activities']}}</td>
                <td>{{$branch['opportunities']}}</td> 
            </tr>
           @endforeach

    </tbody>
</table>
@include('partials._scripts')

@endsection