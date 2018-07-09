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
                    
                     <td><a href="{{route('leads.show',$lead->id)}}">@if($lead->businessname !='')  {{$lead->businessname}} @else {{$lead->companyname}} @endif</a></td>
                  
                    <td>{{$lead->address->address}}</td>
                    <td>{{$lead->address->city}}</td>
                    <td>{{$lead->address->state}}</td>
                   
                    
                    
                </tr>  
                @endforeach
             
            </tbody>



        </table>
    </div>

</div>
@include('partials._scripts')
@endsection
