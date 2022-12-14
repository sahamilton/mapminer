@extends('site.layouts.default')
@section('content')
<div class="container">
<h2>About Mapminer</h2>

<p><strong>Description: </strong>The Mapminer system is designed to give the sales teams insight into the locations of the national accounts that we serve. This information should make it easier to find new opportunities to service our largest accounts. In addition Mapminer provides information on the Branches and Sales Organization.  Addtionally there is information provided from purchased lists of current construction projects through out North America.</p> 

<p>Mapminer was developed by <a href="{{config('mapminer.website')}}" target="_blank" title="Learn more about {{config('mapminer.developer')}}">{{config('mapminer.developer')}}</a> for {{config('mapminer.client')}}</p>

<p>Contact <a href="mailto:{{config('mapminer.system_contact')}}" title="Email {{config('mapminer.support')}}">{{config('mapminer.support')}}</a> for any training or support issues.</p>
<fieldset><legend>Technical Details</legend>
<p><strong>Mapminer Version:</strong> </p>
<p><strong>Mapminer Branch: </strong>  @php echo ucwords(exec('git rev-parse --abbrev-ref HEAD'));@endphp </p>
<p><a href ="https://pingping.io/PuUkRgfn" target="_blank"><strong>Status</strong></a></p>
@if(auth()->user()->hasRole('admin'))
<p><strong>Environment: </strong>
{{App::environment()}} </p>
<p><strong>Laravel Version:</strong>  {{App::version()}}</p>
    <p><strong>Branch:</strong>
        @php echo ucwords(exec('git rev-parse --abbrev-ref HEAD'));@endphp</p>
    <p><strong>
        <a href="{{route('versions.index')}}" title="See all versions">Git Version:</a>
    </strong> {{$version}} </p>

    <p><strong>PHP Version:</strong> {{ phpversion()}} </p>
    @php
    $results = \DB::select( \DB::raw("select version()") );
    $db_version = $results[0]->{'version()'};
    @endphp
    <p><strong>Database Version:</strong> {{$db_version}}</p> 
    <p><strong>Server Address:</strong> {{$_SERVER['SERVER_ADDR']}}</p>
    <p><strong>Server Name:</strong> {{gethostname()}}</p>
    <p><strong>Database:</strong> {{env('DB_DATABASE')}}</p>

@endif
</fieldset>
</div>

@endsection
