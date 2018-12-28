<h4>Missing Managers</h4>


	<table class="table" id ="nosorttable">
		<thead>
			
			<th>Manager</th>
			<th>Managers Employee Id</th>
		
		</thead>
		<tbody>
			@foreach($data['noManagers'] as $manager)

				<tr>
					
					<td>{{$manager->manager}}</td>
					<td>{{$manager->mgr_emp_id}}</td>
					

				</tr>
			@endforeach
		</tbody>
	</table>
	
