<table>
<tbody>
	<tr>
	<th>Branch Id</th>
	<th>Branch Name</th>
	<th>Serviceline</th>
	<th>Address</th>
	<th>City</th>
	<th>State</th>
	<th>Branch Manager</th>
	<th>Business Manager</th>
	<th>Market Manager</th>
	</tr>


	@foreach($branches as $branch)

		<tr>

			<td>{{$branch->id}}</td>
			<td>{{$branch->branchname}}</td>
			<td>
				<ul style=" list-style-type: none;">
				@foreach ($branch->servicelines as $serviceline)
				<li>{{$serviceline->ServiceLine}}</li>
				@endforeach
			</ul>
			<td>{{$branch->street}} {{$branch->address2}}</td>
			<td>{{$branch->city}}</td>
			<td>{{$branch->state}}</td>
			<td>
				@if(isset($branch->branchmanager))
					@foreach ($branch->branchmanager as $manager)
						@if(! $loop->first),@endif
						{{$manager->fullName()}}
				@endforeach
				@endif
			
			</td>
			<td>
				@if(isset($branch->businessmanager))
					@foreach ($branch->businessmanager as $manager)
						@if(! $loop->first),@endif
						{{$manager->fullName()}}
					@endforeach
				@endif
			</td>
			<td>
				@if(isset($branch->marketmanager))
					@foreach ($branch->marketmanager as $manager)
					@if(! $loop->first),@endif
						{{$manager->fullName()}}
					@endforeach
				@endif
			</td>

		</tr>
	@endforeach
</tbody>


</table>
