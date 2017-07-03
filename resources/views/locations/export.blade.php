@extends('admin.layouts.default')
@section('content')
<div class="container">
	<h2>Export locations for a national account</h2>
	<ol>
	<li>Select the company that locations belong to from the list</li>

	</ol>
	<form method="post" name="exportlocations" action ="{{route('companies.export')}}" >
	{{csrf_field()}}
		<div class="form-group{{ $errors->has('company)') ? ' has-error' : '' }}">
			<label class="col-md-4 control-label">Select Company</label>
			<div class="input-group input-group-lg ">
				<select class="form-control" name='company'>
					@foreach ($companies as $key=>$company))
						<option value="{{$key}}">{{$company}}</option>

					@endforeach
				</select>
				<span class="help-block">
					<strong>{{ $errors->has('company') ? $errors->first('company') : ''}}</strong>
				</span>
			</div>

	

		<input type="submit" class="btn btn-success" name="submit" value="Export Locations">
	</div>
	</form>

</div>

@stop
