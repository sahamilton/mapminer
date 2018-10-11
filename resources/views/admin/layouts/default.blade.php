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
<<<<<<< HEAD
        <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" 
		integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" 
		crossorigin="anonymous">
		<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.2/css/jquery.dataTables.css">
       <link rel="stylesheet" href="{{asset('assets/css/responsive-tables.css')}}">
       
        <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css" />        
        <link rel="stylesheet" href="{{asset('assets/css/prmapminer.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('maps/css/map.css')}}">
=======

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
>>>>>>> development
<!-- jQuery -->

		<script  src="https://code.jquery.com/jquery-3.2.1.min.js" 
		integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="  crossorigin="anonymous"></script>
<<<<<<< HEAD
		<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<!-- DataTables -->
		<link href="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.css" rel="stylesheet">
		<script src="//cdnjs.cloudflare.com/ajax/libs/summernote/0.8.3/summernote.js"></script>
<!-- Calendar -->
		<script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js"></script>
		<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.js"></script>
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.2.7/fullcalendar.min.css"/>


<!-- DataTables -->
<script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>

=======
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


>>>>>>> development






		<style>
        body {
            padding: 0  0;
<<<<<<< HEAD
        }
=======
            min-height:100vh;
        }

>>>>>>> development
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
<<<<<<< HEAD
	</head>

	<body>
		<!-- To make sticky footer need to wrap in a div -->
		<div id="wrap">
		@include ('admin.partials._navbar')

		<!-- Container -->
		<div class="container">
=======
		<style CSS> 
		body {
		min-height: 100vh; 
		}
	</style>
	</head>

<body>

	<!-- To make sticky footer need to wrap in a div -->
<div id="app">
	@include ('admin.partials._navbar')

	<!-- Container -->
	<div class="container">
		<main class="py-4" style="min-height: 100vh; ">
			
>>>>>>> development
			<!-- Notifications -->
			@include('notifications')
			<!-- ./ notifications -->

			<!-- Content -->
			@yield('content')
			<!-- ./ content -->
<<<<<<< HEAD
		</div>
		<!-- ./ container -->
</div>

@if (config('app.debug') && auth()->check() && config('app.env')=='local') 

    @include('sudosu::user-selector')
@endif
<!-- the following div is needed to make a sticky footer -->

		<div id="push"></div>

		<!-- ./wrap -->


	   @include('site.layouts.footer')

            


		<!-- Javascripts
		================================================== -->
=======
			

		@if (config('app.debug') && auth()->check() && config('app.env')=='local' )
			@include('sudosu::user-selector')
		@endif
		
		</main>	
       </div>
       @include('site.layouts.footer')     
@include('admin.partials._scripts')
   

    
>>>>>>> development
<script
    src="//d2s6cp23z9c3gz.cloudfront.net/js/embed.widget.min.js"
    data-domain="trueblue.besnappy.com"
    data-lang="en"
<<<<<<< HEAD
	data-name="{{ isset(Auth::user()->firstname) ? Auth::user()->firstname ." ". Auth::user()->lastname  : Auth::user()->username  }}"  data-email="{{ isset(Auth::user()->email) ? Auth::user()->email : '' }}"  ></script>
   <!-- Import typeahead.js -->
    <script src="//twitter.github.io/typeahead.js/releases/latest/typeahead.bundle.js"></script>

    <!-- Initialize typeahead.js on the input -->
    <script>
        $(document).ready(function() {
            var bloodhound = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.whitespace,
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: '/ops/user/find?q=%QUERY%',
                    wildcard: '%QUERY%'
                },
            });
            
            $('#search').typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            }, {
                name: 'users',
                source: bloodhound,
                display: function(data) {
                    return data.username  //Input value to be set when you select a suggestion. 
                },
                templates: {
                    empty: [
                        '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                    ],
                    header: [
                        '<div class="list-group search-results-dropdown">'
                    ],
                    suggestion: function(data) {
                        var url = '{{ route("person.details", ":slug") }}';

                        url = url.replace(':slug', data.person.id);
                    return '<div style="font-weight:normal; margin-top:-10px ! important;" class="list-group-item"><a href="'+url+'">'
                         + data.person.firstname + ' ' + data.person.lastname + '</a></div></div>'
                    }
                }
            });
        });
    </script>     

=======
	data-name="{{ isset(auth()->user()->firstname) ? auth()->user()->firstname ." ". auth()->user()->lastname  : auth()->user()->username  }}"  data-email="{{ isset(auth()->user()->email) ? auth()->user()->email : '' }}"  ></script>
>>>>>>> development
        @yield('scripts')
	</body>
</html>
