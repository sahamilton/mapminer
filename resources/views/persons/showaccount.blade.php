@extends('site.layouts.default')
@section('content')

<h3>Accounts managed by {{$people->fullName()}}</h3>
<p><a href="mailto:{{$people->email}}" title="Email {{$people->fullName()}}">{{$people->email}}</a></p>
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<th>Account</th>
		<th>Vertical</th>
	</thead>
	<tbody>
	
		@foreach($accounts as $account)

			<tr>  
				<td>
				@if($account->countlocations)
					<a title="See all {{$account->companyname}} locations" 
					href="{{route('company.show',$account->id)}}">
					{{$account->companyname}}
					</a>
				@else
					{{$account->companyname}}
				@endif
				</td>			
				
				<td>
				@if(isset($account->industryVertical->filter))	
					<a href="{{route('company.vertical',$account->industryVertical->id)}}" 
					title="See all {{$account->industryVertical->filter}} accounts">
					{{$account->industryVertical->filter}}</a>
				@else	
					Not Assigned
				@endif
				</td>
			</tr>
		@endforeach

	</tbody>
</table>

@include('partials/_scripts')
@endsection
