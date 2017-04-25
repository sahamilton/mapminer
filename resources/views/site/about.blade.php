@extends('site.layouts.default')
@section('content')
<div class="container">
<h2>About Mapminer</h2>
<p><strong>Environment: </strong>
{{App::environment()}} </p>
<p><strong>Laravel Version:</strong> <?php  $laravel = app(); echo $laravel::VERSION;?></p>
<p><strong>Mapminer Version:</strong> v 2.5 </p>
<p><strong>PHP Version</strong>{{ phpversion()}} </p> 
<p><strong>Server Address:</strong>
{{$_SERVER['SERVER_ADDR']}}</p>


</div>

@endsection