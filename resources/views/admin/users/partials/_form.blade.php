
<<<<<<< HEAD
		<!-- CSRF Token -->
		<input type="hidden" name="_token" value ="{{{ csrf_token() }}}" />
		<!-- ./ csrf token -->
    <ul class="nav nav-tabs">
    <li class="active"><a data-toggle="tab" href="#home">UserDetails</a></li>
    <li><a data-toggle="tab" href="#menu1">Personal Details</a></li>
     <li><a data-toggle="tab" href="#menu2">Industry Verticals</a></li>
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
=======
<!-- CSRF Token -->
<input type="hidden" name="_token" value ="{{{ csrf_token() }}}" />
<!-- ./ csrf token -->
<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" data-toggle="tab" href="#home" role="tab">UserDetails</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu1" role="tab">Personal Details </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" data-toggle="tab" href="#menu2" role="tab">Industry Verticals</a>
  </li>
</ul>
<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
    <h3>User Details</h3>
    @include ('admin.users.partials._userform')
  </div>
  <div class="tab-pane fade" id="menu1" role="menu1" aria-labelledby="person-tab">
    <h3>Personal Details</h3>
    @include ('admin.users.partials._personform')
  </div>
  <div class="tab-pane fade" id="menu2" role="menu2" aria-labelledby="industry-tab">
    <h3>Industry Verticals</h3>
    @include ('admin.users.partials._verticalform')

</div>
</div>
  
>>>>>>> development
    @include('partials._scripts')