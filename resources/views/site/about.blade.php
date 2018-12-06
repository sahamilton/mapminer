@extends('site.layouts.default')
@section('content')
<div class="container">
<h2>About Mapminer</h2>
<p><strong>Description: </strong>The People Ready Mapminer system is designed to give the sales teams insight into the locations of the national accounts that we serve. This information should make it easier to find new opportunities to service our largest accounts. In addition Mapminer provides information on the People Ready Branches and Sales Organization.  Addtionally there is information provided from purchased lists of current construction projects through out North America.</p> 

<p>Mapminer was developed by <a href="//www.ELAConsultingGroup.com" target="_blank" title="Learn more about ELA">ELA Consulting Group</a> for People Ready</p>

<p>Contact Sales Operations for any training or support issues.</p>
<fieldset><legend>Technical Details</legend>
<p><strong>Mapminer Version:</strong> <?php echo trim(exec('git tag'));?></p>

@if(auth()->user()->hasRole('Admin'))
	<p><strong>Environment: </strong>
	{{App::environment()}} </p>
	<p><strong>Laravel Version:</strong>  {{App::version()}}</p>
	<p><strong>Branch:</strong>
		<?php echo ucwords(exec('git rev-parse --abbrev-ref HEAD'));?></p>
	<p><strong>
		<a href="{{route('versions.index')}}" title="See all versions">Git Version:</a>
	</strong> {{$version}} </p>

	<p><strong>PHP Version:</strong> {{ phpversion()}} </p> 
	<p><strong>Server Address:</strong> {{$_SERVER['SERVER_ADDR']}}</p>
	<p><strong>Server Name:</strong> {{gethostname()}}</p>
	<p><strong>Database:</strong> {{env('DB_DATABASE')}}</p>
@endif
</fieldset>
</div>

@endsection