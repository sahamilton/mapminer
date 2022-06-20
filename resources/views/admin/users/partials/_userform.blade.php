<div class="m-4">
<!-- Email -->
@if(isset($user))
@bind($user)
@endif
	@if(auth()->user()->hasRole(['admin']))
	<x-form-checkbox
		class="col-md-8" 
		type="checkbox" 
		checked
		name="oracle" 
		id="oracle" 
		value="1"
		label="Oracle Validation:" />
		
	@else
		<input hidden name="oracle" value=1/>
	@endif
		
	<x-form-input
		class="col-md-10" 
		type="email"
		required
		name="email"
		label="Email:"
		placeholder="email@peopleready.com"
		
		autocomplete="off" />

	<x-form-input
		class="col-md-10" 
		type="text"
		required
		name="employee_id"
		label="Employee ID:"
		placeholder="GUI#"
		 />



	<x-form-checkbox
		class="col-md-2" 
		type="checkbox" 
		name="confirmed" 
		id="confirmed" 
		checked
		value="1"
		label="Active:"
		 />


	<x-form-select
		class="col-md-10" 
		name="roles[]"
		multiple
		required
		many-relation
		:options="$roles->pluck('display_name', 'id')->toArray()"
		label="Roles:"

		/>
	<x-form-select
		required
        class="col-md-10" 
        name="serviceline[]" 
        id="serviceline" 
        multiple
        many-relation
        :options="$servicelines"
        label="Service Lines:" 
         />

</div>
@if(isset($user))
@endbind
@endif
