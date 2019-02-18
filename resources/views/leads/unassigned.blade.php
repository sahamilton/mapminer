@extends ('admin.layouts.default')
@section('content')
<div class="container">
 
   <h2>{{$leadsource->source}} Unassigned Leads</h2>

    <p>There are {{number_format($leadsource->unassigned,0)}} unassigned leads from this list of a total {{number_format($leadsource->addresses_count,0)}}.</p>
    <table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    	<thead>
    		<th>State</th>
    		<th>Count</th>
    		<th>Assign</th>
    	</thead>
    	<tbody>
    		@foreach ($states as $state=>$count)
    		<tr>
    			<td><a href="{{route('leadsource.unassigned.state',['leadsource'=>$leadsource->id,'state'=>$state])}}" title = "View all unassigned {{$state}} leads">{{$state}}</a></td>
    			<td>{{$count}}</td>
    			<td>Assign</td>
    		</tr>
    		@endforeach
    	</tbody>
    </table>

<a href="{{route('leadsource.assign',$leadsource->id)}}" class="btn btn-info">Assign Geographically</a>
</div>

@include('partials._scripts')
@endsection
