<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Basic Page Needs
	================================================== -->
	@include('site.layouts.partials._meta')
	<!-- CSS
	================================================== -->

	<link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.css" />
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" />    
	<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<link href="{{asset('assets/css/summernote.css')}}" rel="stylesheet" />
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet" />  
	<link rel="stylesheet" href="{{asset('assets/css/prmapminer.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{asset('maps/css/map.css')}}" />

	
	<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>

	<!-- jQuery -->


	<script type="text/javascript" src="//code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>

	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
	
	<script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<!-- DataTables -->
	<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.0-rc.1/js/jquery.dataTables.min.js" ></script>
	<script src="{{asset('assets/js/summernote.min.js')}}"></script>
	<script src="{{asset('assets/js/starrr.js')}}"></script>
	<script src="{{asset('assets/js/bootstrap-colorpicker.min.js')}}"></script>
	<script src="//cdn.jsdelivr.net/algoliasearch/3/algoliasearchLite.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>




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

		<!-- Container -->
	<div class="container">
    
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
</div>	</div>


		@include('partials.besnappy')

		@yield('scripts')
	</body>
</html>
