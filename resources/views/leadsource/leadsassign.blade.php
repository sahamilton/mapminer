@extends ('admin.layouts.default')
@section('content')
<h2>Assignable Leads from {{$leads[0]->leadsource->source}} Source </h2>
<p><a href="{{route('leadsource.index')}}">Return to all Leads sources</a></p>
<form method = "post" action = "{{route('leads.assignbatch')}}" >
{{csrf_field()}}
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
     
    <th>Company</th>
    <th>Business Name</th>
    <th>Location</th>

    <th>Closest Reps (in miles)</th>
    <th>Closest Branches (in miles)</th>

  
       
    </thead>
    <tbody>

 @foreach($leads as $lead)

    <tr>  
    <td><a href="{{route('leads.show',$lead->id)}}">{{$lead->companyname}}</a></td>
    <td>{{$lead->businessname}}</td>
    <td>{{$lead->city}}, {{$lead->state}}</td>
    <td>{{number_format($data['reps'][$lead->id][0]->distance_in_mi,0)}}
    <input type="checkbox" name="salesrep[]" value="{{$lead->id}}" />
    </td>
    <td><input type="checkbox" name="branch[]" value="{{$lead->id}}" />
    {{number_format($data['branches'][$lead->id][0]->distance_in_mi,0)}}
    </td>
    </tr>
   @endforeach
    
    </tbody>
    </table>
    <input type = "submit" class = "btn btn-success" name="submit" value="Assign Checked Leads" />
</form>
@include('partials._scripts')
@endsection