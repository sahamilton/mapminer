@extends('admin.layouts.default')
@section('content')
<div class= "container">
	<h2>Missing {{count($missingPeople)}} People</h2>

	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<th>Unmatched Id</th>
			<th>Valid People</th>
		</thead>
		<tbody>
		@foreach ($missingPeople as $missing)

			<tr>
				<td>{{$missing->person_id}}</td>
				<td>
					<div class="form-group">
						<div class="col-md-6">
							<select  class="form-control" name='person[{{$missing->person_id}}]'>
								@foreach ($people as $person))
									<option value="{{$person->id}}">{{$person->fullName()}}</option>
								@endforeach
							</select>
						</div>
					</div>
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>
@stop
