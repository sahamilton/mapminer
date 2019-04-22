@extends('admin.layouts.default')
@section('content')
<div class="container">
<div class="col-sm-6">
	<h2>{{$person->fullName()}}'s Direct Reports</h2>
<div class="alert-danger"><p>You must re-assign these reports before you can delete {{$person->firstname}}</p></div>

<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
	<thead>
		<tr>
			<th>Direct Report</th>
			
		</tr>
	</thead>
	<tbody>
		@foreach($person->directReports as $report)
		<tr>
			<td><a href="{{route('users.edit',$report->user_id)}}">{{$report->fullName()}}</a></td>
			
		</tr>
		@endforeach
	</tbody>
</table>
</div>
</div>
@include('partials._scripts')
@endsection