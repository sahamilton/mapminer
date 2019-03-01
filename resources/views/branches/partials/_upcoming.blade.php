<div class="container" style="clear:both">
	<div class="col-sm-8">
		<h2>Upcoming Follow Up</h2>
		<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
			<thead>
				<th>Date</th>
				<th>Company</th>
				<th>Address</th>
			</thead>
			<tbody>
				@foreach($data['upcoming'] as $activity)
				<tr>
					<td>{{$activity->followup_date->format('Y-m-d')}}</td>
					<td><a href="{{route('address.show',$activity->relatesToAddress->id)}}">{{$activity->relatesToAddress->businessname}}</a></td>
					<td><a href="{{route('address.show',$activity->relatesToAddress->id)}}">{{$activity->relatesToAddress->fullAddress()}}</a></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>