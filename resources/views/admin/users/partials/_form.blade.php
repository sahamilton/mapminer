
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value ="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->
    <ul class="nav nav-tabs">
    <li class="nav-item active"><a class="nav-link active" data-toggle="tab" href="#home">UserDetails</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#menu1">Personal Details</a></li>
    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#menu2">Industry Verticals</a></li>
    </ul>

		<div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <h3>User Details</h3>
      	@include ('admin.users.partials._userform')
		    
    </div>
    <div id="menu1" class="tab-pane fade">
      <h3>Personal Details</h3>
    	@include ('admin.users.partials._personform')
     
    </div>
    <div id="menu2" class="tab-pane fade">
      <h3>Industry Verticals</h3>
      @include ('admin.users.partials._verticalform')
     
    </div>
    </div>
    @include('partials._scripts')