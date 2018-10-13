@extends('site/layouts/default')
@section('content')

<h2> {{$people->postName()}}</h2>
<p><strong>Industry Focus:</strong>
<ul>
@foreach ($people->industryfocus as $vertical)
<li>{{$vertical->filter}}</li>
@endforeach
</ul></p>
<p>Reports to: 
	@if($people->reportsTo)
<a href="{{route('person.show',$people->reportsTo->id)}}">
		

{{$people->reportsTo->postName()}}</a>
@endif
<p><a href="mailto:{{$people->email}}" title="Email {{$people->firstname}} {{$people->lastname}}">{{$people->email}}</a> </p>
<h4>Branches serviced by {{$people->postName()}}</h4>


  <p><a href="{{route('showmap.person',$people->id)}}"><i class="far fa-flag" aria-hidden="true"></i> Map View</a></p>	

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
	    <th>Branch</th>
	    <th>Number</th>
	    <th>Service Line</th>
	    <th>Branch Address</th>
	    <th>City</th>
	    <th>State</th>
    </thead>
    <tbody>
   @foreach($people->branchesServiced as $branch)
    <tr>  
	<td>
		<a title="See details of branch {{$branch->branchname}}"
		href="{{route('branches.show',$branch->id)}}" >
			{{$branch->branchname}}
		</a>
	</td>
	
	<td>{{$branch->id}}</td>
	<td>
		<ul>
			@foreach($branch->servicelines as $serviceline)
				<li><a href="route('serviceline.account',$serviceline->id)}}" 
				title="See all {{$serviceline->ServiceLine}} branches">
					{{$serviceline->ServiceLine}}
				</a></li>
			@endforeach
		</ul>
	</td>
	<td>{{$branch->street}} {{$branch->address2}}</td>
	<td>{{$branch->city}}</td>
	<td>
		<a href="route(branch/statemap,$branch->state)" title="See all {{$branch->state}} branches">
			{{$branch->state}}
		</a>
	</td>
    </tr>
   @endforeach
    
    </tbody>
    </table>





@include('partials/_scripts')
@endsection
