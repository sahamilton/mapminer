@extends('site/layouts/default')
@section('content')

<div class="float-right">
		 <p><a href="{{ route('branches.index') }}">Show all branches</a></p>	
		</div>
        
<h4>Branches managed by {{$people->firstname}} {{$people->lastname}}</h4>
<p><a href="mailto:{{$people->email}}" title="Email {{$people->firstname}}">{{$people->email}}</a></p>  


<p><a href="{{route('showmap.person',$people->id)}}"><i class="far fa-flag" aria-hidden="true"></i> Map View</a></p>	

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Branch</th>
		<th>Number</th>
		<th>Service Line</th>
		<th>Branch Address</th>
		<th>City</th>
		<th>State</th>
		<th>Sales Team</th>
	</thead>
    <tbody>
   @foreach($people->branchesServiced as $branch)
    <tr>  
	

		<td><a href="{{route('branches.show',$branch->id)}}">{{$branch->branchname}}</a></td>
		<td>{{$branch->id}}</td>
		<td>{{$branch->brand}}</td>
		<td>{{$branch->street}}</td>
		<td>{{$branch->city}}</td>
		<td>{{$branch->state}}</td>
		<td>{{$branch->servicedBy->count()}}</td>

    </tr>
   @endforeach
    
    </tbody>
    </table>





@include('partials/_scripts')

@endsection
