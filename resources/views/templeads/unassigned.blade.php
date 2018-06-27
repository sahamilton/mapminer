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
               
                   @foreach ($leads as $lead)
                <tr> 

                     <td><a href="{{route('salesrep.newleads.show',$lead->id)}}">{{isset($lead->companyname) ? $lead->companyname : $lead->businessname}}</a></td>
                    <td>{{$lead->address}}</td>
                    <td>{{$lead->city}}</td>
                    <td>{{$lead->state}}</td>
                   
                    
                    
                </tr>  
                @endforeach
             
            </tbody>



        </table>
    </div>

</div>
@include('partials._scripts')
@endsection
