<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Basic Page Needs frontend layouts default
		================================================== -->
		@include('site.layouts.partials._meta')
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
<script type="text/javascript" charset="utf8" src="//ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.2.min.js"></script> -->
 <script type="text/javascript" src="{{asset('/assets/js/jquery.js')}}"></script>
<!-- DataTables 
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.0-rc.1/js/jquery.dataTables.js" />-->
     <script type="text/javascript" src="{{asset('/assets/js/jquery.dataTables.min.js')}}"></script>





		<style>
        body {
            padding: 0  0;
        }
		@section('styles')
		@show
		</style>

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

                     @if (!auth()->check())
						<li {{ (Request::is('/') ? ' class="active"' : '') }}><a href="{{{ URL::to('') }}}">Welcome</a></li>
                        @endif
                        @if (auth()->check())

                        <li {{ (Request::is('/company') ? ' class="active"' : '') }}><a href="{{{ URL::to('company') }}}">Accounts</a></li>
                        <li {{ (Request::is('/branch') ? ' class="active"' : '') }}><a href="{{{ URL::to('branch') }}}">Branches</a></li>
                        <li {{ (Request::is('/branch') ? ' class="active"' : '') }}><a href="{{{ URL::to('person') }}}">People</a></li>
                       
                        @endif
					</ul>

 @if (auth()->check())
					<ul class="nav navbar-nav float-right">
 						
                        @if (auth()->user()->hasRole('admin'))
                         <li class="dropdown{{ (Request::is('admin/users*|admin/roles*') ? ' active' : '') }}">
    						<a class="dropdown-toggle" data-toggle="dropdown" href="{{{ URL::to('admin/users') }}}">
    							<i class="far fa-user" aria-hidden="true"></i> Users <span class="caret"></span>
    						</a>
    						<ul class="dropdown-menu">
    							<li{{ (Request::is('admin/users*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/users') }}}"><i class="far fa-user" aria-hidden="true"></i> Users</a></li>
    							<li{{ (Request::is('admin/roles*') ? ' class="active"' : '') }}><a href="{{{ URL::to('admin/roles') }}}"><i class="far fa-user" aria-hidden="true"></i> Roles</a></li>

    						</ul>
    					</li>
                        @endif
    					<li class="divider-vertical"></li>
    					<li class="dropdown">
    							<a class="dropdown-toggle" data-toggle="dropdown" href="#">

    								<i class="far fa-user" aria-hidden="true"></i> {{{ auth()->user()->fullName() }}}	<span class="caret"></span>
    							</a>
    							<ul class="dropdown-menu">
    								<li><a href="{{{ URL::to('user/settings') }}}"><i class="far fa-wrench" aria-hidden="true"></i> Profile</a></li>
    								<li class="divider"></li>
    								<li><a href="{{{ URL::to('user/logout') }}}"><i class="far fa-share" aria-hidden="true"></i> Logout</a></li>

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
	        <p class="muted credit">&copy; <?php echo date("Y");?>  Mapminer Development Corp, llc </a>.</p></div>
            	    </div>
          </div>


            


		<!-- Javascripts
		================================================== -->

        <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>

        @yield('scripts')
	</body>
</html>
