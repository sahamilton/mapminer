@extends ('admin.layouts.default')
@section('content')
<div class="container">
 
    <h2>Branch {{$branches->first()->branches->branchname}} Leads Overview</h2>
  
    @if($branches->first()->branches->manager->count()>0)

    <h4>Branch Managed By {{$branches->first()->branches->manager->first()->postName()}}</h4>
    @endif
    <p><a href='{{route("newleads.branch.map",$branches->first()->branches->id)}}'>
<<<<<<< HEAD
 <i class="fa fa-flag" aria-hidden="true"></i> Map view</a></p>
     <p><a href='{{route("leads.branch")}}'>
 <i class="fa fa-th-list" aria-hidden="true"></i> See All Branch Leads</a></p>
=======
 <i class="far fa-flag" aria-hidden="true"></i> Map view</a></p>
     <p><a href='{{route("leads.branch")}}'>
 <i class="fas fa-th-list" aria-hidden="true"></i> See All Branch Leads</a></p>
>>>>>>> development
    <div class="col-md-10 col-md-offset-1">
        <table class="table" id = "sorttable">
            <thead>
               
                <th>Company Name</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th>Rating</th>

            </thead>
            <tbody>
                @foreach ($branches as $lead)
                   
                <tr> 

                     <td><a href="{{route('salesrep.newleads.show',$lead->id)}}">{{$lead->companyname}}</a></td>
                    <td>{{$lead->address}}</td>
                    <td>{{$lead->city}}</td>
                    <td>{{$lead->state}}</td>
                    @if($lead->salesrep->count()>0)
                        <td><a href="{{route('salesrep.newleads',$lead->salesrep->first()->id)}}">{{$lead->salesrep->first()->postName()}}</a></td>
                        <td>{{$leadStatuses[$lead->salesrep->first()->pivot->status_id]}}</td>
                        <td>{{$lead->salesrep->first()->pivot->ranking}}</td>
                    @else
                        <td>Unassigned</td>
                        <td></td>
                        <td></td>

                    @endif
                    
                    
                </tr>  

                @endforeach
            </tbody>



        </table>
    </div>

</div>
@include('partials._scripts')
@endsection
