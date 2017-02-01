<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        @include('partials.navbar')

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
    <script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script>
$(document).on('show.bs.modal','#confirm-delete', function(e) {
      $(this).find('.danger').attr('href', $(e.relatedTarget).data('href'));
    $(this).find('#title').html($(e.relatedTarget).data('title'));
  }); 
  </script>
</body>
</html>
