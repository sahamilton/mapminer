@extends('admin.layouts.default')
@section('content')
<div class= "container">
	<h2>Missing Branches</h2>
	<table class="table table-striped table-bordered table-condensed">
		<thead>
			<th>Unmatched Id</th>
			<th>Valid Branches</th>
		</thead>
		<tbody>
		@foreach ($missingBranches as $missing)
			<tr>
				<td>{{$missing->branch_id}}</td>
				<td>
					<div class="form-group">
						<div class="col-md-6">
							<select  class="form-control" name='branch[{{$missing->branch_id}}]'>
								@foreach ($branches as $key=>$value))
									<option value="{{$key}}">{{$value}}</option>
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
