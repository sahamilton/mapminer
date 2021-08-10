<div class="form">

	<form 

	class="inline"
	 method="post" 
	 action="{{route('branches.dashboard')}}" >
	@csrf
		<div class="form-group row col-sm-8 inline align-middle">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<i class="fab fa-pagelines" aria-hidden="true"></i>
				</span>
			</div>
			<select  
			class=""  
			id="branchselect" 
			name="branch" 
			onchange="this.form.submit()">
				
				@foreach ($data['mybranches'] as $key=>$branchname)

					<option {{isset($branch->id) && $branch->id == $key ? 'selected' :''}} value="{{$key}}">{{$branchname}}</option>
				
				@endforeach 
			</select>
		</div>
	</form>
</div>