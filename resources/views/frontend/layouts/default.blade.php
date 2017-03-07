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
		<meta name="description" content="A private system for TrueBlue, Inc designated employees and their agents only.  The system maps national account locations to branches." />

		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- CSS
		================================================== -->
        
        <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap-theme.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/mapminer.css')}}">
                <link rel="stylesheet" type="text/css" href="{{asset('maps/css/map.css')}}">
       
        <!-- DataTables CSS 
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.0-rc.1/css/jquery.dataTables.css">-->
 <link rel="stylesheet" type="text/css" href="{{asset('/assets/css/jquery.dataTables.css')}}">
<!-- jQuery
<script type="text/javascript" charset="utf8" src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script> -->
 <script type="text/javascript" src="{{asset('/assets/js/jquery.js')}}"></script>
<!-- DataTables 
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.0-rc.1/js/jquery.dataTables.js" />-->
     <script type="text/javascript" src="{{asset('/assets/js/jquery.dataTables.min.js')}}"></script>





		<style>
        body {
            padding: 60px 0;
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
		<div id="wrap">
		<!-- Navbar -->
		<div class="navbar navbar-default navbar-inverse navbar-fixed-top"><div style="width:80;position:relative;float:left"><img src="{{{ asset('assets/img/tblogosm.png')}}}" /></div>
			 <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav">
                     @if (!Auth::check())
						<li {{ (Request::is('/') ? ' class="active"' : '') }}><a href="{{{ URL::to('') }}}">Welcome</a></li>
                        @endif
                        @if (Auth::check())
                        <li {{ (Request::is('/company') ? ' class="active"' : '') }}><a href="{{{ URL::to('company') }}}">Accounts</a></li>
                        <li {{ (Request::is('/branch') ? ' class="active"' : '') }}><a href="{{{ URL::to('branch') }}}">Branches</a></li>
                        <li {{ (Request::is('/branch') ? ' class="active"' : '') }}><a href="{{{ URL::to('person') }}}">People</a></li>
                       
                        @endif
					</ul>
 @if (Auth::check())
					<ul class="nav navbar-nav pull-right">
 						
                        @if (Auth::user()->hasRole('Admin'))
                         <li class="dropdown{{ (Request::is('admin/users*|admin/roles*') ? ' active' : '') }}">
    						<a class="dropdown-toggle" data-toggle="dropdown" href="{{{ URL::to('admin/users') }}}">
    							<span class="glyphicon glyphicon-user"></span> Users <span class="caret"></span>
    						</a>
    						<ul class="dropdown-menu">
    							<li{{ (Request::is('admin/users*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/users') }}}"><span class="glyphicon glyphicon-user"></span> Users</a></li>
    							<li{{ (Request::is('admin/roles*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/roles') }}}"><span class="glyphicon glyphicon-user"></span> Roles</a></li>
    						</ul>
    					</li>
                        @endif
    					<li class="divider-vertical"></li>
    					<li class="dropdown">
    							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
    								<span class="glyphicon glyphicon-user"></span> {{{ Auth::user()->username }}}	<span class="caret"></span>
    							</a>
    							<ul class="dropdown-menu">
    								<li><a href="{{{ URL::to('user/settings') }}}"><span class="glyphicon glyphicon-wrench"></span> Profile</a></li>
    								<li class="divider"></li>
    								<li><a href="{{{ URL::to('user/logout') }}}"><span class="glyphicon glyphicon-share"></span> Logout</a></li>
    							</ul>
    					</li>
    				</ul>
                    @endif
					<!-- ./ nav-collapse -->
				</div>
                
			</div>
		</div>
		<!-- ./ navbar -->

		<!-- Container -->
		<div class="container">
			<!-- Notifications -->
			@include('notifications')
			<!-- ./ notifications -->

			<!-- Content -->
			@yield('content')
			<!-- ./ content -->
		</div>
		<!-- ./ container -->

		<!-- the following div is needed to make a sticky footer -->
		<div id="push"></div>
		</div>
		<!-- ./wrap -->


	    <div id="footer" >
	      <div class="container">
	        <p class="muted credit">&copy; <?php echo date("Y");?>  ELA Consulting Group, LLC / TrueBlue, Inc</a>.</p></div>
            	    </div>
          </div>


            


		<!-- Javascripts
		================================================== -->

        <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>

        @yield('scripts')
	</body>
</html>
