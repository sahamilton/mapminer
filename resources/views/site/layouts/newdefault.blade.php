<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Basic Page Needs layouts default
		================================================== -->
		@include('site.layouts.partials._meta')
		<!-- CSS
		================================================== -->
		<link href="{{ asset('css/app.css') }}" rel="stylesheet">
		 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

	 
		<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" />    
		<script src="https://kit.fontawesome.com/{{config('mapminer.fontawesome')}}.js" crossorigin="anonymous"></script>

		
		<link rel="stylesheet" href="{{asset('assets/css/prmapminer.css')}}" />
		<link rel="stylesheet" type="text/css" href="{{asset('maps/css/map.css')}}" />


		<!-- jQuery -->


		<script type="text/javascript" src="//code.jquery.com/jquery-3.3.1.min.js"
	  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
	  crossorigin="anonymous"></script>

		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		
		<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

		<!-- Alpine -->

		<script src="//unpkg.com/alpinejs" defer></script>

		<!-- Charts -->
		<script type="text/javascript" 
		src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js">
		
		</script>
		@livewireStyles

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Favicons
		================================================== -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{{ asset('assets/ico/apple-touch-icon-144-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{{ asset('assets/ico/apple-touch-icon-114-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{{ asset('assets/ico/apple-touch-icon-72-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" href="{{{ asset('assets/ico/apple-touch-icon-57-precomposed.png') }}}">
		<link rel="shortcut icon" href="{{{ asset('assets/ico/favicon.png') }}}">

	</head>
	
	<body>
	<div id="app">
		@include('site.layouts.partials._googletagmanager')
		
		@if(config('app.env')=='production')
		@include('site.layouts.partials._fullstory')
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
