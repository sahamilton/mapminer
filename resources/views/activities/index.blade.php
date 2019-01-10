@extends('site.layouts.default')
@section('content')
<h1>Activities</h1>  
<table id='sorttable' class ='table table-bordered table-striped table-hover'>
	<thead>
		
		
		<th>Company</th>
		<th>Address</th>
		<th>Date</th>
		<th>Follow up date</th>
		<th>Contact</th>
		<th>Activity</th>
	</thead>
	<tbody>
		@foreach ($activities as $activity)
			
			<tr>
				<td>
					<a href="{{route('address.show',$activity->relatesToAddress->id)}}">
						{{$activity->relatesToAddress->businessname}}
					</a>
				</td>
				<td>{{$activity->relatesToAddress->fulladdress()}}</td>
				<td>{{$activity->activity_date->format('Y-m-d')}}</td>
				
				<td>
					@if($activity->followup_date)
					{{$activity->followup_date->format('Y-m-d')}}
					@endif
				</td>
				<td>
					@if($activity->relatedContact->count()>0)
						@foreach ($activity->relatedContact as $contact)
							{{$contact->fullname}}
						@endforeach
					@endif
				</td>
					
				<td>{{$activity->type->activity}}</td>
				

			</tr>
		@endforeach
	</tbody>

</table>
   
@include('partials/_scripts')

@endsection