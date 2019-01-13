@extends ('admin.layouts.default')
@section('content')
<div class="container">
 
   <h2>{{$leadsource->source}} Unassigned Leads</h2>

    <div class="col-md-10 col-md-offset-1">
        <table class="table" id = "sorttable">
            <thead>
               
                <th>Company Name</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
               

            </thead>
            <tbody>
               
                   @foreach ($leadsource->unassignedLeads as $lead)
                    
                <tr> 
                    
                     <td><a href="{{route('leads.show',$lead->id)}}">
                        {{$lead->businessname !='' ?  $lead->businessname : $lead->companyname}}</a></td>
                  
                    <td>{{$lead->address}}</td>
                    <td>{{$lead->city}}</td>
                    <td>{{$lead->state}}</td>
                   
                    
                    
                </tr>  
                @endforeach
             
            </tbody>



        </table>
    </div>
<a href="{{route('leadsource.assign',$leadsource->id)}}" class="btn btn-info">Assign Geographically</a>
</div>

@include('partials._scripts')
@endsection
