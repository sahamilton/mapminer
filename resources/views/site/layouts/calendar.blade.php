<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Basic Page Needs
		================================================== -->
		@include('site.layouts.partials._meta')
		<!-- CSS
		================================================== -->
        
        <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/r-2.2.2/datatables.min.css"/>
	<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" />    
	<link rel="stylesheet" href="//use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

	<link href="{{asset('assets/css/summernote.css')}}" rel="stylesheet" />
	<link rel="stylesheet" href="{{asset('assets/css/bootstrap-colorpicker.min.css')}}" rel="stylesheet" />  
	<link rel="stylesheet" href="{{asset('assets/css/prmapminer.css')}}" />
	<link rel="stylesheet" type="text/css" href="{{asset('maps/css/map.css')}}" />
	<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>
<!-- jQuery -->

	<script src="//code.jquery.com/jquery-1.10.1.min.js"></script>
    <script src="{{asset('maps/js/handlebars-1.0.0.min.js')}}"></script>
    
    <script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    
    <script src="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

	<!-- DataTables -->
	<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/r-2.2.2/datatables.min.js"></script>
	<script src="{{asset('assets/js/summernote.min.js')}}"></script>
	<script src="{{asset('assets/js/starrr.js')}}"></script>
	<script src="{{asset('assets/js/bootstrap-colorpicker.min.js')}}"></script>
	<script src="//cdn.jsdelivr.net/algoliasearch/3/algoliasearchLite.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
	<!---- Charts --->

	<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" ></script>
<!-- Calendar Io -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
	    <script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
	    

	<!-- Charts -->
	<script type="text/javascript" 
	src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js">
	
	</script>
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
   

		<!-- To make sticky footer need to wrap in a div -->

		<div id="app">
			@include('site.layouts.partials._googletagmanager')
		@include ('site.layouts._navbar')

		<!-- Container -->
		<main class="py-4">

			<!-- Notifications -->
			@include('notifications')
			<!-- ./ notifications -->

			<!-- Content -->
			@yield('content')

		</main>
		<div class="clear"></div>	<!-- ./ content -->
			@include('site.layouts.footer')
		
		<!-- ./ container -->
</div>

@include('partials.besnappy')
            	    </div>
          </div>


            


		<!-- Javascripts
		================================================== -->



        @yield('scripts')
	</body>
</html>
