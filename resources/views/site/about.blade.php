@extends('site.layouts.default')
@section('content')
<div class="container">
<h2>About Mapminer</h2>
<p><strong>Environment: </strong>
{{App::environment()}} </p>
<p><strong>Laravel Version:</strong>  {{App::version()}}</p>
<p><strong>Mapminer Version:</strong> {{config('app.version')}} </p>
<p><strong>PHP Version:</strong> {{ phpversion()}} </p> 
<p><strong>Server Address:</strong>
{{$_SERVER['SERVER_ADDR']}}</p>
<p><strong>Server Name:</strong> {{gethostname()}}</p>


</div>

@endsection