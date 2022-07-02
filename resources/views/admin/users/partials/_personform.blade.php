
<div class="m-4">
@if(isset($user))
	@bind($user->person)
@endif
	<x-form-input
		class="col-md-10" 
		type="text"
		required
		name="firstname"
		label="First Name:"
		
	 />

	 <x-form-input
		class="col-md-10" 
		type="text"
		required
		name="lastname"
		label="Last Name:"
		
	 />

	  <x-form-input
		class="col-md-10" 
		type="text"
		required
		placeholder="Full address with city & state"
		name="address"
		label="Full address" 
		value="{{isset($user) ? $user->person->fullAddress() : ''}}"
		
	 />

	 <x-form-input
		class="col-md-10" 
		type="text"		
		name="phone"
		label="Phone #" 
		
		
	 />
	 <x-form-input
		class="col-md-10" 
	 	required
		type="text" 
		name="business_title" 
		label="Business title:"
		placeholder="Business title"
		
		/>
		
	<x-form-select
		required
		name="reports_to"
		label="Reports To:"
		:options="$managers" 
		placeholder=" Select ...."
		
		/>

	<h4>Branches</h4>
	<p>select from the list of branches</p>

	<x-form-select
		multiple 
		many-relation
		name="branchesServiced[]"
		label="Branches Managed:"
		:options="$branches"
		placeholder="Choose..." 
		
		/>
	<p>or enter a comma separated list of branches.</p>
	<x-form-input
		class="col-md-10" 
		type="text"
		
		name="branchstring" 
		id="branchstring"
		
	 />
@if(isset($user))
		@endbind
@endif
</div>
