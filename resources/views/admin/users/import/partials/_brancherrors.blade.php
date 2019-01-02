<form name="fixusercreateerrors" method="post" action="{{route('fixusercreateerrors')}}" >
		@csrf
		<table class="table">
			<thead>
				<th>Person</th>
				<th>Employee Id</th>
				<th>Branches</th>
				<th><input type="checkbox" id="checkAll">Delete Branches</th> 
				<th>Errors</th>
				
				<th></th>
			</thead>
			<tbody>
				@foreach ($import as $person)

				<tr>
					<td>{{$person->firstname}} {{$person->lastname}}</td>
					<td>{{$person->employee_id}}</td>
					<td><input type="text" name="branch[{{$person->employee_id}}]" value="{{$person->branches}}" >
					</td>
					<td><input type="checkbox" name="ignore[{{$person->employee_id}}]" />
					<td class="text text-danger"><label>Unnknown Branch</label>
						@foreach ($importerrors[$person->employee_id] as $invalid)
							{{$invalid}}
							@if(! $loop->last),@endif
						@endforeach
						<i class="fas fa-exclamation-triangle text text-danger"
							title="Invalid branches."
							></i>
					</td>

					
				</tr>
				@endforeach
			</tbody>
		</table>
		<input type="hidden" name="type" value="branch" />
		<input class="btn btn-success" name="submit" type="submit" value="update import errors" >
	</form>
</div>