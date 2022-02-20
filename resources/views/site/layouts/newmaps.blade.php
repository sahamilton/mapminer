<!DOCTYPE html>
<html>
  <head>
    @include('site.layouts.partials._meta')
    <link rel="stylesheet" type="text/css" href="{{ asset('storelocator/assets/css/storelocator.css')}}" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" />
	<link rel="stylesheet" href="{{asset('assets/css/prmapminer.css')}}" />    
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="storelocatorassets/js/libs/handlebars.min.js"></script>
    <script src="//maps.googleapis.com/maps/api/js?key=AIzaSyC5GUlsLwW3cH2vuXeQfpagQgSOcnp8Nbo"></script>
    <script src="storelocatorassets/js/plugins/storeLocator/jquery.storelocator.js"></script>
    <script src="https://kit.fontawesome.com/cf4e43f4c0.js" crossorigin="anonymous"></script>	
  </head>

  <body>

    <div id="app">
		
		@if(config('app.env')=='production')
		@include('site.layouts.partials._fullstory')
		@include('site.layouts.partials._googletagmanager')
		@endif
		<!-- To make sticky footer need to wrap in a div -->

		@include ('site.layouts._navbar')
		@include ('site.layouts._alert')

		<!-- Container -->
		<div class="container">

			@include('partials._newsflash')
			<main class="py-4 flex-grow">

				<!-- Notifications -->
				@include('notifications')
				<!-- ./ notifications -->

				<!-- Content -->
				@yield('content')
				<!-- ./ content -->
			</main>		
			<div class="clear"></div>
			@include('site.layouts.footer')
		</div>	
	</div>

	@livewireScripts
	@include('partials.besnappy')
	
	@yield('scripts')

	</body>
</html>