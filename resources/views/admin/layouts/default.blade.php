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
		<meta name="keywords" content="National Accounts, True Blue Inc,Branch & Location Mapping " />
		<meta name="author" content="Stephen Hamilton, ELA Consulting Group,LLC" />
		<meta name="description" content="A private system for TrueBlue, Inc designated employees anad their agents only.  The system maps national account locations based on any US or Canadian geo coordinates." />

		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- CSS
		================================================== -->


<!-- jQuery -->
        <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" />         
<!-- Bootstrap -->
		<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<!-- Datatables -->
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.css">
       <link rel="stylesheet" href="{{asset('assets/css/responsive-tables.css')}}">
<!-- SummerNote -->
		<link href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
		
<!-- Calendar -->
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>       
        <link rel="stylesheet" href="{{asset('assets/css/prmapminer.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('maps/css/map.css')}}">
<!-- FontAwesome -->		
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">        
        <!-- Javascript
        =====================================================- -->

<!-- jQuery -->

		<script  src="https://code.jquery.com/jquery-3.2.1.min.js" 
		integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="  crossorigin="anonymous"></script>

<!-- GoogleAPIS -->
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<!-- Bootstrap -->
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!-- DataTables -->
		<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>
<!-- SummerNote -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
<!-- Calendar -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
		<script src="//twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>


		<style>
        body {
            padding: 0  0;

            min-height:100vh;
        }


		@section('styles')
		@show
		</style>

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


	</head>

<body>

	<!-- To make sticky footer need to wrap in a div -->
<div id="app">
	@include ('admin.partials._navbar')

	<!-- Container -->
	<div class="container">
		<main class="py-4 flex-grow">
			

			<!-- Notifications -->
			@include('notifications')
			<!-- ./ notifications -->

			<!-- Content -->
			@yield('content')
			<!-- ./ content -->

			

		@if (config('app.debug') && auth()->check() && config('app.env')=='local' )
			@include('sudosu::user-selector')
		@endif
		
		</main>	
		<div class="clear" style="border:solid red 1px;margin-top: 80px"></div>

       @include('site.layouts.footer')     
@include('admin.partials._scripts')
      </div>    

</div>
    

<script
    src="//d2s6cp23z9c3gz.cloudfront.net/js/embed.widget.min.js"
    data-domain="trueblue.besnappy.com"
    data-lang="en"
	data-name="{{ isset(auth()->user()->firstname) ? auth()->user()->firstname ." ". auth()->user()->lastname  : auth()->user()->username  }}"  data-email="{{ isset(auth()->user()->email) ? auth()->user()->email : '' }}"  ></script>
        @yield('scripts')
	</body>
</html>
