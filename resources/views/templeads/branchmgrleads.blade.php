@extends ('site.layouts.default')
@section('content')
<div class="container">
    <h2>Branch Leads Overview</h2>
    <h4>Branches Managed By {{$branchmgr->postName()}}</h4>
    <p><a hef="{{route('leads.branch')}}">Return to all branches!!</a></p>

    <div class="col-md-10 col-md-offset-1">
        <table class="table" id = "sorttable">
            <thead>
                <th>Branch</th>
                <th>Company Name</th>
                
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th>Rating</th>

            </thead>
            <tbody>
                @foreach ($branchleads as $lead)
              
                <tr> 
                    <td>{{$lead->branches->branchname}}</td>
                    <td><a href="{{route('salesrep.newleads.show',$lead->id)}}">{{$lead->companyname}}</a></td>
                    <td>{{$lead->address}}</td>
                    <td>{{$lead->city}}</td>
                    <td>{{$lead->state}}</td>
                    @if($lead->salesrep)
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
