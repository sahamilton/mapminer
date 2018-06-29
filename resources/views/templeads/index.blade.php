@extends ('admin.layouts.default')
@section('content')
<div class="container">
    <h2>{{$leadsource->source}} Overview</h2>
    
    <h4><a href="{{route('leadsource.branches',$leadsource->id)}}">See {{$leadsource->source}} by Branches</a></h4>
    <h4><a href="{{route('leadsource.index')}}">Return to All LeadSources</a></h4>
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
                @foreach ($data as $rep)
                
                <tr> 
                    <td><a href="{{route('salesrep.newleads', $rep['id'])}}">{{$rep['Name']}}</a></td>
                   
                        <td></td><td></td>
                  
                     <td>{{$rep['Total']}}</td>
                     <td>{{isset($rep['Owned']) ? $rep['Owned'] :''}}</td>
                     <td>{{isset($rep['Closed']) ? $rep['Closed'] : ''}}</td>
                     <td>
                        
                            {{$rep['Ranking']}}
               
                    </td>
                </tr>  

                @endforeach
            </tbody>



        </table>
    </div>

</div>
@include('partials._scripts')
@endsection
