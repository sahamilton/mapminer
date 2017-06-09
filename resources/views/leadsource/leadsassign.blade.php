@extends ('admin.layouts.default')
@section('content')
<h2>Assignable Leads from {{$leads[0]->leadsource->source}} Source </h2>
<p><a href="{{route('leadsource.index')}}">Return to all Leads sources</a></p>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>Company</th>
    <th>Business Name</th>
    <th>Location</th>

    <th>Closest Reps</th>

  
       
    </thead>
    <tbody>

 @foreach($leads as $lead)

    <tr>  
    <td><a href="{{route('leads.show',$lead->id)}}">{{$lead->companyname}}</a></td>
    <td>{{$lead->businessname}}</td>
    <td>{{$lead->city}} {{$lead->state}}</td>
    <td>{{number_format($data[$lead->id][0]->distance_in_mi,0)}} miles</td>
    
    </tr>
   @endforeach
    
    </tbody>
    </table>
@include('partials._scripts')
@endsection