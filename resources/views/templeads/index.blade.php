@extends ('admin.layouts.default')
@section('content')
<div class="container">
    <h2>Leads Overview</h2>
    


    <div class="col-md-10 col-md-offset-1">
        <table class="table" id = "sorttable">
            <thead>

                <th>Sales Rep</th>
                
                <th>Manager</th>
                <th>Manager Role</th>
                <th>All Leads</th>
                <th>Open Leads</th>
                <th>Closed Leads</th>
                <th>Rating</th>

            </thead>
            <tbody>
                @foreach ($reps as $rep)

                <tr> 
                    <td><a href="{{route('salesrep.newleads',$rep->id)}}">{{$rep->postName()}}</a></td>
                    @if($rep->reportsTo)
                        <td>{{$rep->reportsTo->postName()}}</td>
                        <td>{{$rep->reportsTo->userdetails->roles->first()->name}}</td>
                        @else
                        <td></td><td></td>
                    @endif
                     <td>{{$rep->templeads_count}}</td>
                     <td>{{$rep->openleads_count}}</td>
                     <td>{{$rep->closedleads_count}}</td>
                     <td>
                        @if(isset($rankings[$rep->id]))
                            {{$rankings[$rep->id]}}
                        @endif
                    </td>
                </tr>  

                @endforeach
            </tbody>



        </table>
    </div>

</div>
@include('partials._scripts')
@endsection
