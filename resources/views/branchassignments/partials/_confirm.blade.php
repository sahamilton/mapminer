
<form class="form-inline" 
    action="{{route('branchassignments.update',$details->user_id)}}" 
    method="post" 
    name="branchassignment">
	@method('put')
	@csrf

	<input type="hidden" name="branches" value="{{$branches}}">
		<input type="submit" 
		name="submit" 
		class="btn btn-info" 
		value="Confirm Correct" />
		
</form>