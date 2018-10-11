<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Basic Page Needs
		================================================== -->
		<meta charset="utf-8" />
		<title>
			@section('title')
			{{env('APP_NAME')}}
			@show
		</title>
		<meta name="keywords" content="National Accounts, True BLue Inc,Branch Mapping " />
		<meta name="author" content="Stephen Hamilton, ELA Consulting Group,LLC" />
		<meta name="description" content="A private system for TrueBlue, Inc designated employees anad their agents only.  The system maps national account locations to branches." />

		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- CSS
		================================================== -->
 <meta name="csrf-token" content="{{ csrf_token() }}">
<<<<<<< HEAD
        <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap-theme.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/prmapminer.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('maps/css/map.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap-colorpicker.min.css')}}">
	 <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="{{asset('maps/js/handlebars-1.0.0.min.js')}}"></script>
    <script src="https://maps.google.com/maps/api/js?key={{config('maps.api_key')}}"></script>
=======
 	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap-theme.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/prmapminer.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('maps/css/map.css')}}">
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap-colorpicker.min.css')}}">
	 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="{{asset('maps/js/handlebars-1.0.0.min.js')}}"></script>
    <script src="https://maps.google.com/maps/api/js?key={{config('maps.api_key')}}"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
>>>>>>> development
    <script src="{{asset('maps/js/jquery.storelocator.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap-colorpicker.min.js')}}"></script>
       
      
<<<<<<< HEAD





		<style>
        body {
            padding: 0  0;
        }
		@section('styles')
		@show
		</style>

=======
>>>>>>> development
		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Favicons
		================================================== -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{{ asset('assets/ico/apple-touch-icon-144-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{{ asset('assets/ico/apple-touch-icon-114-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{{ asset('assets/ico/apple-touch-icon-72-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" href="{{{ asset('assets/ico/apple-touch-icon-57-precomposed.png') }}}">
		<link rel="shortcut icon" href="{{{ asset('assets/ico/favicon.png') }}}">
<<<<<<< HEAD
	</head>

	<body onLoad="load()"> 
=======

	
	</head>
	

	<body onLoad="load()">
<div id="app">
>>>>>>> development
@include('site.layouts.partials._googletagmanager')
@if(config('app.env')=='production')
	@include('site.layouts.partials._fullstory')
@endif
<<<<<<< HEAD


		<!-- To make sticky footer need to wrap in a div -->
		<div id="wrap">
		@include ('site.layouts._navbar')


		<!-- Container -->
		<div class="container">
=======
@include ('site.layouts._navbar')
<div class="container">
<main class="py-4 flex-grow">

		


		<!-- Container -->

>>>>>>> development

			<!-- Notifications -->
			@include('notifications')
			<!-- ./ notifications -->

			<!-- Content -->
			@yield('content')
			<!-- ./ content -->
<<<<<<< HEAD
		</div>
=======
>>>>>>> development
		<!-- ./ container -->
 @if (config('app.debug') && auth()->check() && config('app.env')=='local')
    @include('sudosu::user-selector')
@endif
<<<<<<< HEAD
		<!-- the following div is needed to make a sticky footer -->
		<div id="push"></div>
		</div>
		<!-- ./wrap -->


	    @include('site.layouts.footer')
            	    

=======

</main>
<div style="clear:both"></div>
	    @include('site.layouts.footer')
   </div>        	    
</div>
>>>>>>> development

 <script
    src="https://d2s6cp23z9c3gz.cloudfront.net/js/embed.widget.min.js"
    data-domain="trueblue.besnappy.com"
    data-lang="en"
<<<<<<< HEAD
	data-name="{{ isset(Auth::user()->person->firstname) ? Auth::user()->person->firstname ." ". Auth::user()->person->lastname  : Auth::user()->username  }}"
    data-email="{{ isset(Auth::user()->email) ? Auth::user()->email : '' }}"
=======
	data-name="{{ isset(auth()->user()->person->firstname) ? auth()->user()->person->firstname ." ". auth()->user()->person->lastname  : auth()->user()->username  }}"
    data-email="{{ isset(auth()->user()->email) ? auth()->user()->email : '' }}"
>>>>>>> development
    >
</script>
            
            


		<!-- Javascripts
		================================================== -->

<<<<<<< HEAD
        <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>

=======
>>>>>>> development
        @yield('scripts')
	</body>
</html>
