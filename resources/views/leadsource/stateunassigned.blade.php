@extends ('admin.layouts.default')
@section('content')
<div class="container">
 <p><a href="{{route('leadsource.show',$leadsource->id)}}">Return to {{$leadsource->source}} details</a></p>
<h4>{{$leadsource->source}} Unassigned Leads in {{$state}}</h4>

    <table id ='sorttable2' class='table table-striped table-bordered table-condensed table-hover'>
        <thead>
         
        <th>Company</th>
        <th>Company Name</th>
        <th>City</th>
        <th>State</th>
        <th>Date Created</th>
           
        </thead>
        <tbody>

            @foreach($leadsource->addresses as $lead )

                    <tr>  
                        <td><a href="{{route('address.show',$lead->id)}}">{{$lead->companyname}}</a></td>
                        <td><a href="{{route('address.show',$lead->id)}}">{{$lead->businessname}}</a></td>
                        <td>{{$lead->city}}</td>
                        <td>{{$lead->state}}</td>
                        <td>{{$lead->created_at->format('M j, Y')}}</td>
                    </tr>

            @endforeach
        
        </tbody>
    </table>
    

    <p><a href="{{route('leadsource.assign',$leadsource->id)}}"><button class="btn btn-info"  > Assign Leads Geographically</button></a></p>


@include('partials._scripts')
@endsection
