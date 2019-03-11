@extends('admin.layouts.default')

@section('content')

<div class= 'container' style="padding-bottom:40px;">
<table id ='sorttable' class='table table-striped table-bordered table-condensed table-hover'>
    <thead>
    	<th>Company</th>
    	<th>Follow Up</th>
    </thead>
    <tbody>
    	@foreach($users as $user)

    		@foreach($user->activities as $activity)	
    		<tr>
    			<td>{{$activity->relatesToAddress->businessname}}</td>
    			<td>{{$activity->followup_date}}</td>
    		</tr>
    		@endforeach
    		@endforeach
    	</tbody>
    </table>


</table>
</div>
@endsection()