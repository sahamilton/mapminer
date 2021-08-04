<div class="form">
	<form 

	class="inline"
	 method="post" 
	 action="{{route('dashboard.setManager')}}" >
	@csrf
		<div class="form-group row col-sm-8 inline align-middle">
			<div class="input-group-prepend">
				<span class="input-group-text">
					<i class="fas fa-user-friends"></i>
				</span>
			</div>
			<select  
			class=""  
			id="managerselect" 
			name="manager" 
			onchange="this.form.submit()">
				<option>Select Manager</option>
					@foreach ($data['team'] as $mgr)
						<option value="{{$mgr->id}}">{{$mgr->fullName()}}</option>
					@endforeach 
			</select>
		</div>
	</form>
</div>